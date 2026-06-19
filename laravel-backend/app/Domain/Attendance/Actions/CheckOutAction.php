<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\Attendance\Data\VisitDebitResult;
use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Domain\WorkspaceClient\Actions\UpsertWorkspaceClientFromVisitAction;
use App\Domain\WorkspaceSubscription\Actions\DeductWorkspaceSubscriptionAction;
use App\Domain\WorkspaceSubscription\Actions\ExpireWorkspaceSubscriptionAction;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use App\Jobs\RefreshWorkspaceDailyVisitStatsJob;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class CheckOutAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
        private SubscriptionRepositoryInterface $subscriptions,
        private WorkspaceSubscriptionRepositoryInterface $workspaceSubscriptions,
        private DeductWorkspaceSubscriptionAction $deductWorkspace,
        private ExpireWorkspaceSubscriptionAction $expireWorkspace,
        private UpsertWorkspaceClientFromVisitAction $upsertWorkspaceClient,
    ) {}

    public function handle(string $visitId, string $userId): WorkspaceVisit
    {
        return DB::transaction(function () use ($visitId, $userId): WorkspaceVisit {
            // Row-locked AND ownership-scoped: a user can only check out their own visit.
            $visit = $this->visits->lockVisitForUser($visitId, $userId);
            if ($visit === null) {
                throw new NotFoundHttpException('Visit not found.');
            }

            // Idempotent: checking out an already-closed visit returns it, no second deduction.
            if ($visit->status === VisitStatus::CHECKED_OUT) {
                return $visit->load('workspace');
            }

            $visit->loadMissing('workspace');
            $workspace = $visit->workspace;

            // Server time is authoritative — client-sent durations are ignored.
            $seconds = (int) abs($visit->check_in_at->diffInSeconds(now()));
            $rawMinutes = intdiv($seconds, 60);

            // Calculate billing minutes based on the rules:
            // 1. Less than 5 mins is completely free (0 minutes).
            // 2. More than 5 mins and up to 60 mins is calculated as 60 minutes (1 hour).
            // 3. More than 60 mins is calculated as the real time.
            $billingMinutes = 0;
            if ($rawMinutes > 5) {
                $billingMinutes = max(60, $rawMinutes);
            }

            // Multiplier depends on the funding source captured at check-in:
            //   workspace sub -> always 1.0; global sub -> workspace multiplier; free -> 0.
            $source = $visit->billing_source ?? BillingSource::FREE;
            $multiplier = match ($source) {
                BillingSource::WORKSPACE_SUBSCRIPTION => 1.0,
                BillingSource::GLOBAL_SUBSCRIPTION => (float) ($workspace->hour_multiplier ?? 1.0),
                default => 0.0,
            };

            // Daily cap is in REAL consumed minutes, summed across ALL sources today.
            $dailyCapRealMinutes = (int) (($workspace->day_calculation_hours ?? 8) * 60);
            $usedRealToday = $this->visits->todayBillableMinutesForUserInWorkspace($userId, $workspace->id);
            $allowedRealMinutes = min($billingMinutes, max(0, $dailyCapRealMinutes - $usedRealToday));

            $billableMinutes = 0;
            $deductedMinutes = 0;

            if ($multiplier > 0.0 && $allowedRealMinutes > 0) {
                if ($source === BillingSource::WORKSPACE_SUBSCRIPTION && $visit->workspace_subscription_id !== null) {
                    $debitResult = $this->debitWorkspace($visit, $allowedRealMinutes);
                    $deductedMinutes = $debitResult->deductedMinutes;
                    $billableMinutes = $debitResult->fundedRealMinutes;
                } elseif ($source === BillingSource::GLOBAL_SUBSCRIPTION && $visit->subscription_id !== null) {
                    $debitResult = $this->debitGlobal($visit, $userId, $allowedRealMinutes, $multiplier);
                    $deductedMinutes = $debitResult->deductedMinutes;
                    $billableMinutes = $debitResult->fundedRealMinutes;
                }
            } elseif ($source === BillingSource::FREE) {
                // Free visits are fully funded by definition
                $billableMinutes = $allowedRealMinutes;
                $deductedMinutes = 0;
            } elseif ($multiplier === 0.0) {
                // Workspace has multiplier 0.0, visits are free
                $billableMinutes = $allowedRealMinutes;
                $deductedMinutes = 0;
            }

            $visit->update([
                'status' => VisitStatus::CHECKED_OUT,
                'check_out_at' => now(),
                'duration_minutes' => $rawMinutes,
                'billable_minutes' => $billableMinutes,
                'deducted_minutes' => $deductedMinutes,
                'hour_multiplier_applied' => $multiplier,
                'active_flag' => null,
            ]);
            $this->upsertWorkspaceClient->handle($visit->id);

            // Expiry is deferred while a subscription funds an open visit. Close the
            // visit first, then finalize expiry while still in the same transaction.
            if ($source === BillingSource::WORKSPACE_SUBSCRIPTION && $visit->workspace_subscription_id !== null) {
                $workspaceSubscription = $this->workspaceSubscriptions->lockById($visit->workspace_subscription_id);
                if ($workspaceSubscription?->expires_at?->isPast()) {
                    $this->expireWorkspace->handle($workspaceSubscription);
                }
            }

            $this->visits->releaseOccupancy($workspace->id);
            RefreshWorkspaceDailyVisitStatsJob::dispatch($workspace->id, now()->toDateString())->afterCommit();

            Log::info('visit.checked_out', [
                'visit_id' => $visit->id,
                'user_id' => $userId,
                'source' => $source->value,
                'raw' => $rawMinutes,
                'billable' => $billableMinutes,
                'deducted' => $deductedMinutes,
                'multiplier' => $multiplier,
            ]);

            return $visit->fresh()->load('workspace');
        });
    }

    private function debitWorkspace(WorkspaceVisit $visit, int $allowedRealMinutes): VisitDebitResult
    {
        $sub = $this->workspaceSubscriptions->lockById($visit->workspace_subscription_id);
        if (
            $sub === null
            || $sub->workspace_id !== $visit->workspace_id
            || $sub->user_id !== $visit->user_id
        ) {
            throw new \LogicException('Captured workspace subscription is unavailable or does not match the visit.');
        }

        $deducted = min($allowedRealMinutes, $sub->remaining_minutes);
        if ($deducted > 0) {
            $this->deductWorkspace->handle($sub, $deducted, $visit->id);
        }

        return new VisitDebitResult(
            deductedMinutes: $deducted,
            fundedRealMinutes: $deducted,
        );
    }

    private function debitGlobal(WorkspaceVisit $visit, string $userId, int $allowedRealMinutes, float $multiplier): VisitDebitResult
    {
        $sub = $this->subscriptions->lock($visit->subscription_id);
        if ($sub === null || $sub->user_id !== $userId) {
            throw new \LogicException('Captured global subscription is unavailable or does not match the visit.');
        }

        $availableWalletMinutes = $sub->remaining_minutes;

        if ($availableWalletMinutes === null) {
            // Unlimited subscription (time-bound)
            $fundedRealMinutes = $allowedRealMinutes;
            $deductedWalletMinutes = 0;
        } else {
            // Limited subscription (wallet)
            $fundedRealMinutes = min(
                $allowedRealMinutes,
                (int) floor($availableWalletMinutes / $multiplier),
            );
            $deductedWalletMinutes = $fundedRealMinutes > 0
                ? min((int) ceil($fundedRealMinutes * $multiplier), $availableWalletMinutes)
                : 0;
        }

        if ($deductedWalletMinutes > 0) {
            $this->subscriptions->deduct($sub, $deductedWalletMinutes, $visit->id, $userId);
        }

        return new VisitDebitResult(
            deductedMinutes: $deductedWalletMinutes,
            fundedRealMinutes: $fundedRealMinutes,
        );
    }
}
