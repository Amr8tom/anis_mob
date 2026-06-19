<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Repositories;

use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Models\WorkspacePlan;
use Illuminate\Support\Collection;

final class EloquentWorkspacePlanRepository implements WorkspacePlanRepositoryInterface
{
    public function create(array $attributes): WorkspacePlan
    {
        return WorkspacePlan::create($attributes);
    }

    public function update(WorkspacePlan $plan, array $attributes): WorkspacePlan
    {
        $plan->update($attributes);

        return $plan->refresh();
    }

    public function deactivate(WorkspacePlan $plan): WorkspacePlan
    {
        $plan->update(['is_active' => false]);

        return $plan->refresh();
    }

    public function findForWorkspace(string $planId, string $workspaceId): ?WorkspacePlan
    {
        return WorkspacePlan::query()
            ->where('id', $planId)
            ->where('workspace_id', $workspaceId)
            ->first();
    }

    public function activeForWorkspace(string $workspaceId): Collection
    {
        return WorkspacePlan::query()
            ->where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->latest()
            ->get();
    }

    public function allForWorkspace(string $workspaceId): Collection
    {
        return WorkspacePlan::query()
            ->where('workspace_id', $workspaceId)
            ->latest()
            ->get();
    }
}
