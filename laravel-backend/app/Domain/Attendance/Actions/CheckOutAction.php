<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Enums\VisitStatus;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class CheckOutAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
        private SubscriptionRepositoryInterface $subscriptions,
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

            // Server time is authoritative — client-sent durations are ignored.
            // Whole elapsed minutes (floor) computed from seconds for deterministic results.
            $seconds = (int) abs($visit->check_in_at->diffInSeconds(now()));
            $rawMinutes = intdiv($seconds, 60);

            // Load workspace billing config
            $workspace = $visit->workspace;
            $multiplier = (float) ($workspace->hour_multiplier ?? 1.00);
            $dailyCapMinutes = ($workspace->day_calculation_hours ?? 8) * 60;
            $maxDeductedMinutes = (int) ceil($dailyCapMinutes * $multiplier);

            // Sum of deducted_minutes for all checked-out visits today at this workspace
            $alreadyDeductedToday = $this->visits->todayDeductedMinutesForUserInWorkspace($userId, $workspace->id);
            $remainingDeduction = max(0, $maxDeductedMinutes - $alreadyDeductedToday);
            $potentialDeduction = (int) ceil($rawMinutes * $multiplier);

            $deductedMinutes = min($potentialDeduction, $remainingDeduction);
            $billableMinutes = $multiplier > 0 ? (int) ceil($deductedMinutes / $multiplier) : $rawMinutes;

            if ($deductedMinutes > 0) {
                if ($subscription = $this->subscriptions->lockActiveForUser($userId)) {
                    $this->subscriptions->deduct($subscription, $deductedMinutes, $visit->id, $userId);
                }
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

            Log::info('visit.checked_out', [
                'visit_id' => $visit->id,
                'user_id' => $userId,
                'raw' => $rawMinutes,
                'billable' => $billableMinutes,
                'deducted' => $deductedMinutes,
                'multiplier' => $multiplier,
            ]);

            return $visit->fresh()->load('workspace');
        });
    }
}
