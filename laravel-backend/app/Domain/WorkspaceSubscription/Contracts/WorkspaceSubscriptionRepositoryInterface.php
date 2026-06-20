<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Contracts;

use App\Models\WorkspaceSubscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface WorkspaceSubscriptionRepositoryInterface
{
    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): WorkspaceSubscription;

    /**
     * ACTIVE, not expired, with remaining_minutes > 0 — the funding candidate.
     */
    public function usableActiveForUserWorkspace(string $userId, string $workspaceId): ?WorkspaceSubscription;

    /**
     * Row-locked usable funding candidate. Must be called inside a transaction.
     */
    public function lockUsableActiveForUserWorkspace(string $userId, string $workspaceId): ?WorkspaceSubscription;

    /**
     * Any ACTIVE subscription (regardless of minutes) — blocks a second activation.
     */
    public function activeForUserWorkspace(string $userId, string $workspaceId): ?WorkspaceSubscription;

    /** Walk-in counterpart of usableActiveForUserWorkspace(). */
    public function usableActiveForWalkInWorkspace(string $walkInId, string $workspaceId): ?WorkspaceSubscription;

    /** Walk-in counterpart of lockUsableActiveForUserWorkspace(). */
    public function lockUsableActiveForWalkInWorkspace(string $walkInId, string $workspaceId): ?WorkspaceSubscription;

    /** Walk-in counterpart of activeForUserWorkspace(). */
    public function activeForWalkInWorkspace(string $walkInId, string $workspaceId): ?WorkspaceSubscription;

    public function lockById(string $id): ?WorkspaceSubscription;

    /** @return Collection<int, WorkspaceSubscription> */
    public function expiringWithinDays(string $workspaceId, int $days): Collection;

    /** @return LengthAwarePaginator<int, WorkspaceSubscription> */
    public function paginateForWorkspace(string $workspaceId, int $perPage): LengthAwarePaginator;

    /** @return Builder<WorkspaceSubscription> */
    public function dueForExpiryQuery(): Builder;

    public function activeCountForWorkspace(string $workspaceId): int;

    public function hoursSoldForWorkspace(string $workspaceId): float;
}
