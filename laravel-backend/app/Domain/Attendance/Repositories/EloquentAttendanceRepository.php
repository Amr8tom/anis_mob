<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Repositories;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Enums\VisitStatus;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;

final class EloquentAttendanceRepository implements AttendanceRepositoryInterface
{
    public function findActiveWorkspaceByQrToken(string $qrToken): ?Workspace
    {
        return Workspace::query()
            ->where('qr_token', $qrToken)
            ->where('is_active', true)
            ->first();
    }

    public function activeVisitForUser(string $userId): ?WorkspaceVisit
    {
        return WorkspaceVisit::query()
            ->with('workspace')
            ->where('user_id', $userId)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->first();
    }

    public function createCheckIn(string $userId, string $workspaceId, ?string $subscriptionId): WorkspaceVisit
    {
        return WorkspaceVisit::create([
            'user_id' => $userId,
            'workspace_id' => $workspaceId,
            'subscription_id' => $subscriptionId,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now(),          // server time is authoritative
            'active_flag' => 1,
        ])->load('workspace');
    }

    public function lockVisitForUser(string $visitId, string $userId): ?WorkspaceVisit
    {
        return WorkspaceVisit::query()
            ->where('id', $visitId)
            ->where('user_id', $userId)       // ownership enforced
            ->lockForUpdate()
            ->first();
    }

    public function todayDeductedMinutesForUserInWorkspace(string $userId, string $workspaceId): int
    {
        return (int) WorkspaceVisit::query()
            ->where('user_id', $userId)
            ->where('workspace_id', $workspaceId)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereDate('check_in_at', today())
            ->sum('deducted_minutes');
    }
}
