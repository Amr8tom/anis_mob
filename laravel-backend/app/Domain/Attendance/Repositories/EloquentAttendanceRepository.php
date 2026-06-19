<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Repositories;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\Attendance\Data\VisitFunding;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceStatus;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;

final class EloquentAttendanceRepository implements AttendanceRepositoryInterface
{
    public function activeWorkspaceByQrToken(string $qrToken): ?Workspace
    {
        return Workspace::query()
            ->where('qr_token', $qrToken)
            ->visibleToVisitors()
            ->first();
    }

    public function reserveOccupancy(Workspace $workspace): bool
    {
        if ($workspace->manual_occupancy !== null && $workspace->capacity > 0 && $workspace->manual_occupancy >= $workspace->capacity) {
            return false;
        }

        return Workspace::query()
            ->whereKey($workspace->id)
            ->where('is_active', true)
            ->whereIn('status', [WorkspaceStatus::OPEN->value, WorkspaceStatus::BUSY->value])
            ->when($workspace->capacity > 0, fn ($query) => $query->whereColumn('active_visit_count', '<', 'capacity'))
            ->increment('active_visit_count') === 1;
    }

    public function releaseOccupancy(string $workspaceId): void
    {
        Workspace::query()->whereKey($workspaceId)->update([
            'active_visit_count' => DB::raw('CASE WHEN active_visit_count > 0 THEN active_visit_count - 1 ELSE 0 END'),
        ]);
    }

    public function activeVisitForUser(string $userId): ?WorkspaceVisit
    {
        return WorkspaceVisit::query()
            ->with(['workspace', 'workspaceSubscription'])
            ->where('user_id', $userId)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->first();
    }

    public function createCheckIn(string $userId, string $workspaceId, VisitFunding $funding): WorkspaceVisit
    {
        return WorkspaceVisit::create([
            'user_id' => $userId,
            'workspace_id' => $workspaceId,
            'subscription_id' => $funding->subscriptionId,
            'workspace_subscription_id' => $funding->workspaceSubscriptionId,
            'billing_source' => $funding->billingSource->value,
            'plan_tier_snapshot' => $funding->planTierSnapshot,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now(),          // server time is authoritative
            'active_flag' => 1,
        ])->load(['workspace', 'workspaceSubscription']);
    }

    public function lockVisitForUser(string $visitId, string $userId): ?WorkspaceVisit
    {
        return WorkspaceVisit::query()
            ->where('id', $visitId)
            ->where('user_id', $userId)       // ownership enforced
            ->lockForUpdate()
            ->first();
    }

    public function markCheckoutRequested(WorkspaceVisit $visit, ?string $note = null): WorkspaceVisit
    {
        $visit->update([
            'checkout_requested_at' => now(),
            'checkout_request_note' => $note,
        ]);

        return $visit->refresh();
    }

    public function clearCheckoutRequest(WorkspaceVisit $visit): WorkspaceVisit
    {
        $visit->update([
            'checkout_requested_at' => null,
            'checkout_request_note' => null,
        ]);

        return $visit->refresh();
    }

    public function todayDeductedMinutesForUserInWorkspace(string $userId, string $workspaceId): int
    {
        $startOfDay = now()->startOfDay();

        return (int) WorkspaceVisit::query()
            ->where('user_id', $userId)
            ->where('workspace_id', $workspaceId)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_in_at', '>=', $startOfDay)
            ->where('check_in_at', '<', $startOfDay->copy()->addDay())
            ->sum('deducted_minutes');
    }

    public function todayBillableMinutesForUserInWorkspace(string $userId, string $workspaceId): int
    {
        $startOfDay = now()->startOfDay();

        return (int) WorkspaceVisit::query()
            ->where('user_id', $userId)
            ->where('workspace_id', $workspaceId)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_in_at', '>=', $startOfDay)
            ->where('check_in_at', '<', $startOfDay->copy()->addDay())
            ->sum('billable_minutes');
    }
}
