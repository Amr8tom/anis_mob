<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Contracts;

use App\Models\WorkspacePlan;
use Illuminate\Support\Collection;

interface WorkspacePlanRepositoryInterface
{
    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): WorkspacePlan;

    /** @param array<string, mixed> $attributes */
    public function update(WorkspacePlan $plan, array $attributes): WorkspacePlan;

    public function deactivate(WorkspacePlan $plan): WorkspacePlan;

    public function findForWorkspace(string $planId, string $workspaceId): ?WorkspacePlan;

    /** @return Collection<int, WorkspacePlan> */
    public function activeForWorkspace(string $workspaceId): Collection;

    /** @return Collection<int, WorkspacePlan> */
    public function allForWorkspace(string $workspaceId): Collection;
}
