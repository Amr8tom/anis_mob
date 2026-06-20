<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\WorkspaceClient\Actions\UpsertWorkspaceClientFromVisitAction;
use App\Domain\WorkspaceSubscription\Actions\DeductWorkspaceSubscriptionAction;
use App\Domain\WorkspaceSubscription\Actions\ExpireWorkspaceSubscriptionAction;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use App\Exceptions\OwnerVisitException;
use App\Jobs\RefreshWorkspaceDailyVisitStatsJob;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;

/**
 * Workspace owner checks a visitor OUT by hand.
 *
 * Registered app users use the standard checkout flow so Silver/Gold balances
 * are deducted consistently. Walk-ins are closed FREE — unless the owner put the
 * walk-in on a workspace (special) subscription, in which case minutes are
 * deducted from that subscription, capped at the workspace's daily hours.
 */
final readonly class OwnerCheckOutVisitAction
{
    private const MINIMUM_BILLED_MINUTES = 5;

    public function __construct(
        private AttendanceRepositoryInterface $visits,
        private CheckOutAction $checkOut,
        private UpsertWorkspaceClientFromVisitAction $upsertWorkspaceClient,
        private WorkspaceSubscriptionRepositoryInterface $workspaceSubscriptions,
        private DeductWorkspaceSubscriptionAction $deductWorkspace,
        private ExpireWorkspaceSubscriptionAction $expireWorkspace,
    ) {}

    public function handle(Workspace $workspace, string $visitId): WorkspaceVisit
    {
        $registeredUserId = WorkspaceVisit::query()
            ->where('id', $visitId)
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->value('user_id');

        if ($registeredUserId !== null) {
            return $this->checkOut->handle($visitId, (string) $registeredUserId);
        }

        return DB::transaction(function () use ($workspace, $visitId): WorkspaceVisit {
            $visit = WorkspaceVisit::query()
                ->where('id', $visitId)
                ->where('workspace_id', $workspace->id)   // ownership-scoped
                ->where('status', VisitStatus::CHECKED_IN->value)
                ->lockForUpdate()
                ->first();

            if ($visit === null) {
                throw new OwnerVisitException('الزيارة غير موجودة أو مغلقة بالفعل.');
            }

            $rawMinutes = intdiv((int) abs($visit->check_in_at->diffInSeconds(now())), 60);

            // Min-billing rule: <5 min free, otherwise at least one hour.
            $billingMinutes = 0;
            if ($rawMinutes > self::MINIMUM_BILLED_MINUTES) {
                $billingMinutes = max(60, $rawMinutes);
            }

            // Daily cap is cumulative across all of this walk-in's visits today,
            // never beyond the workspace's "ساعات احتساب اليوم".
            $usedToday = $this->visits->todayBillableMinutesForWalkInInWorkspace($visit->walk_in_id, $workspace->id);
            $allowedMinutes = min($billingMinutes, max(0, $workspace->dailyCapMinutes() - $usedToday));

            $source = $visit->billing_source ?? BillingSource::FREE;
            $multiplier = $source === BillingSource::WORKSPACE_SUBSCRIPTION ? 1.0 : 0.0;

            $billableMinutes = 0;
            $deductedMinutes = 0;

            if (
                $source === BillingSource::WORKSPACE_SUBSCRIPTION
                && $visit->workspace_subscription_id !== null
                && $allowedMinutes > 0
            ) {
                $sub = $this->workspaceSubscriptions->lockById($visit->workspace_subscription_id);
                if (
                    $sub !== null
                    && $sub->workspace_id === $visit->workspace_id
                    && $sub->walk_in_id === $visit->walk_in_id
                ) {
                    $deductedMinutes = min($allowedMinutes, $sub->remaining_minutes);
                    if ($deductedMinutes > 0) {
                        $this->deductWorkspace->handle($sub, $deductedMinutes, $visit->id);
                    }
                    $billableMinutes = $deductedMinutes;
                }
            } else {
                // Free / direct-pay: counted toward workspace hours, no balance deducted.
                $billableMinutes = $allowedMinutes;
            }

            $visit->update([
                'status' => VisitStatus::CHECKED_OUT->value,
                'check_out_at' => now(),
                'duration_minutes' => $rawMinutes,
                'billable_minutes' => $billableMinutes,
                'deducted_minutes' => $deductedMinutes,
                'hour_multiplier_applied' => $multiplier,
                'active_flag' => null,
            ]);
            $this->upsertWorkspaceClient->handle($visit->id);

            // Finalize expiry of a now-closed subscription within this transaction.
            if ($source === BillingSource::WORKSPACE_SUBSCRIPTION && $visit->workspace_subscription_id !== null) {
                $closedSub = $this->workspaceSubscriptions->lockById($visit->workspace_subscription_id);
                if ($closedSub?->expires_at?->isPast()) {
                    $this->expireWorkspace->handle($closedSub);
                }
            }

            $this->visits->releaseOccupancy($workspace->id);
            RefreshWorkspaceDailyVisitStatsJob::dispatch($workspace->id, now()->toDateString())->afterCommit();

            return $visit->fresh()->load(['user', 'walkIn']);
        });
    }
}
