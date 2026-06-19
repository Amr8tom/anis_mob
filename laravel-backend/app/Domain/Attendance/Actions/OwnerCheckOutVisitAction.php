<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\WorkspaceClient\Actions\UpsertWorkspaceClientFromVisitAction;
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
 * are deducted consistently. Walk-ins are closed without a subscription charge.
 */
final readonly class OwnerCheckOutVisitAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
        private CheckOutAction $checkOut,
        private UpsertWorkspaceClientFromVisitAction $upsertWorkspaceClient,
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

            $minutes = intdiv((int) abs($visit->check_in_at->diffInSeconds(now())), 60);

            $billingMinutes = 0;
            if ($minutes > 5) {
                $billingMinutes = max(60, $minutes);
            }

            $visit->update([
                'status' => VisitStatus::CHECKED_OUT->value,
                'check_out_at' => now(),
                'duration_minutes' => $minutes,
                'billable_minutes' => $billingMinutes,   // Free: counted toward workspace hours, no balance deducted
                'deducted_minutes' => 0,
                'active_flag' => null,
            ]);
            $this->upsertWorkspaceClient->handle($visit->id);
            $this->visits->releaseOccupancy($workspace->id);
            RefreshWorkspaceDailyVisitStatsJob::dispatch($workspace->id, now()->toDateString())->afterCommit();

            return $visit->fresh()->load(['user', 'walkIn']);
        });
    }
}
