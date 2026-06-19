<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Repositories;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionCodeRepositoryInterface;
use App\Models\WorkspaceSubscriptionCode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentWorkspaceSubscriptionCodeRepository implements WorkspaceSubscriptionCodeRepositoryInterface
{
    public function create(array $attributes): WorkspaceSubscriptionCode
    {
        return WorkspaceSubscriptionCode::create($attributes);
    }

    public function existsByCode(string $code): bool
    {
        return WorkspaceSubscriptionCode::query()->where('code', $code)->exists();
    }

    public function lockByCode(string $code): ?WorkspaceSubscriptionCode
    {
        return WorkspaceSubscriptionCode::query()
            ->where('code', $code)
            ->lockForUpdate()
            ->first();
    }

    public function findForWorkspace(string $id, string $workspaceId): ?WorkspaceSubscriptionCode
    {
        return WorkspaceSubscriptionCode::query()
            ->where('id', $id)
            ->where('workspace_id', $workspaceId)
            ->first();
    }

    public function lockForWorkspace(string $id, string $workspaceId): ?WorkspaceSubscriptionCode
    {
        return WorkspaceSubscriptionCode::query()
            ->where('id', $id)
            ->where('workspace_id', $workspaceId)
            ->lockForUpdate()
            ->first();
    }

    public function paginateForWorkspace(string $workspaceId, int $perPage): LengthAwarePaginator
    {
        return WorkspaceSubscriptionCode::query()
            ->with('plan')
            ->where('workspace_id', $workspaceId)
            ->latest('created_at')
            ->paginate($perPage);
    }
}
