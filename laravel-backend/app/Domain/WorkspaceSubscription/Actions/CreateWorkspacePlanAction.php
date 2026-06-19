<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Domain\WorkspaceSubscription\Data\WorkspacePlanData;
use App\Models\WorkspacePlan;

final readonly class CreateWorkspacePlanAction
{
    public function __construct(private WorkspacePlanRepositoryInterface $plans) {}

    public function handle(string $workspaceId, WorkspacePlanData $data): WorkspacePlan
    {
        return $this->plans->create($data->toAttributes($workspaceId));
    }
}
