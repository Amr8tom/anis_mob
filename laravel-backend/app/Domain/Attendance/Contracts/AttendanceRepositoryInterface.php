<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Contracts;

use App\Domain\Attendance\Data\VisitFunding;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;

interface AttendanceRepositoryInterface
{
    public function activeWorkspaceByQrToken(string $qrToken): ?Workspace;

    public function reserveOccupancy(Workspace $workspace): bool;

    public function releaseOccupancy(string $workspaceId): void;

    public function activeVisitForUser(string $userId): ?WorkspaceVisit;

    public function createCheckIn(string $userId, string $workspaceId, VisitFunding $funding): WorkspaceVisit;

    /**
     * Row-locked visit owned by the user, for safe check-out inside a transaction.
     */
    public function lockVisitForUser(string $visitId, string $userId): ?WorkspaceVisit;

    /**
     * Flag a still-open visit as awaiting owner checkout approval.
     */
    public function markCheckoutRequested(WorkspaceVisit $visit, ?string $note = null): WorkspaceVisit;

    /**
     * Clear a pending checkout request from a still-open visit.
     */
    public function clearCheckoutRequest(WorkspaceVisit $visit): WorkspaceVisit;

    /**
     * Sum of deducted_minutes for all CHECKED_OUT visits by this user
     * at this workspace on the current calendar day.
     */
    public function todayDeductedMinutesForUserInWorkspace(string $userId, string $workspaceId): int;

    /**
     * Sum of billable_minutes (real consumed minutes) for this user's CHECKED_OUT
     * visits at this workspace today — the daily cap is measured against this,
     * regardless of funding source.
     */
    public function todayBillableMinutesForUserInWorkspace(string $userId, string $workspaceId): int;

    /** Walk-in counterpart of todayBillableMinutesForUserInWorkspace(). */
    public function todayBillableMinutesForWalkInInWorkspace(string $walkInId, string $workspaceId): int;
}
