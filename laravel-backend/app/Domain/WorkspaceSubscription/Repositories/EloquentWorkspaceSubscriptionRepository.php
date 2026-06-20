<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Repositories;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\WorkspaceSubscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class EloquentWorkspaceSubscriptionRepository implements WorkspaceSubscriptionRepositoryInterface
{
    public function create(array $attributes): WorkspaceSubscription
    {
        return WorkspaceSubscription::create($attributes);
    }

    public function usableActiveForUserWorkspace(string $userId, string $workspaceId): ?WorkspaceSubscription
    {
        return $this->usableQuery('user_id', $userId, $workspaceId)
            ->latest('started_at')
            ->first();
    }

    public function lockUsableActiveForUserWorkspace(string $userId, string $workspaceId): ?WorkspaceSubscription
    {
        return $this->usableQuery('user_id', $userId, $workspaceId)
            ->latest('started_at')
            ->lockForUpdate()
            ->first();
    }

    public function usableActiveForWalkInWorkspace(string $walkInId, string $workspaceId): ?WorkspaceSubscription
    {
        return $this->usableQuery('walk_in_id', $walkInId, $workspaceId)
            ->latest('started_at')
            ->first();
    }

    public function lockUsableActiveForWalkInWorkspace(string $walkInId, string $workspaceId): ?WorkspaceSubscription
    {
        return $this->usableQuery('walk_in_id', $walkInId, $workspaceId)
            ->latest('started_at')
            ->lockForUpdate()
            ->first();
    }

    /** @return Builder<WorkspaceSubscription> */
    private function usableQuery(string $column, string $id, string $workspaceId): Builder
    {
        return WorkspaceSubscription::query()
            ->where($column, $id)
            ->where('workspace_id', $workspaceId)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->where('remaining_minutes', '>', 0)
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    public function activeForUserWorkspace(string $userId, string $workspaceId): ?WorkspaceSubscription
    {
        return WorkspaceSubscription::query()
            ->where('user_id', $userId)
            ->where('workspace_id', $workspaceId)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->latest('started_at')
            ->first();
    }

    public function activeForWalkInWorkspace(string $walkInId, string $workspaceId): ?WorkspaceSubscription
    {
        return WorkspaceSubscription::query()
            ->where('walk_in_id', $walkInId)
            ->where('workspace_id', $workspaceId)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->latest('started_at')
            ->first();
    }

    public function lockById(string $id): ?WorkspaceSubscription
    {
        return WorkspaceSubscription::query()
            ->where('id', $id)
            ->lockForUpdate()
            ->first();
    }

    public function expiringWithinDays(string $workspaceId, int $days): Collection
    {
        return WorkspaceSubscription::query()
            ->with(['user', 'walkIn'])
            ->where('workspace_id', $workspaceId)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)])
            ->orderBy('expires_at')
            ->get();
    }

    public function paginateForWorkspace(string $workspaceId, int $perPage): LengthAwarePaginator
    {
        return WorkspaceSubscription::query()
            ->with(['user', 'walkIn'])
            ->where('workspace_id', $workspaceId)
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function dueForExpiryQuery(): Builder
    {
        return WorkspaceSubscription::query()
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    public function activeCountForWorkspace(string $workspaceId): int
    {
        return WorkspaceSubscription::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->count();
    }

    public function hoursSoldForWorkspace(string $workspaceId): float
    {
        $minutes = (int) WorkspaceSubscription::query()
            ->where('workspace_id', $workspaceId)
            ->sum('total_minutes');

        return round($minutes / 60, 1);
    }
}
