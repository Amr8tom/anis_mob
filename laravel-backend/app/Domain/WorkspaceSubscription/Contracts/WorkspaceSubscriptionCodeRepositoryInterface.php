<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Contracts;

use App\Models\WorkspaceSubscriptionCode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WorkspaceSubscriptionCodeRepositoryInterface
{
    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): WorkspaceSubscriptionCode;

    public function existsByCode(string $code): bool;

    public function lockByCode(string $code): ?WorkspaceSubscriptionCode;

    public function findForWorkspace(string $id, string $workspaceId): ?WorkspaceSubscriptionCode;

    public function lockForWorkspace(string $id, string $workspaceId): ?WorkspaceSubscriptionCode;

    /** @return LengthAwarePaginator<int, WorkspaceSubscriptionCode> */
    public function paginateForWorkspace(string $workspaceId, int $perPage): LengthAwarePaginator;
}
