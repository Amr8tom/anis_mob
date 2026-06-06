<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Contracts;

use App\Models\Workspace;
use App\Models\WorkspaceVisit;

interface AttendanceRepositoryInterface
{
    public function findActiveWorkspaceByQrToken(string $qrToken): ?Workspace;

    public function activeVisitForUser(string $userId): ?WorkspaceVisit;

    public function createCheckIn(string $userId, string $workspaceId, ?string $subscriptionId): WorkspaceVisit;

    /**
     * Row-locked visit owned by the user, for safe check-out inside a transaction.
     */
    public function lockVisitForUser(string $visitId, string $userId): ?WorkspaceVisit;
}
