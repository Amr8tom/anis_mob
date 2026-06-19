<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Models\WorkspacePlan;

final readonly class DeactivateWorkspacePlanAction
{
    public function __construct(private WorkspacePlanRepositoryInterface $plans) {}

    public function handle(WorkspacePlan $plan): WorkspacePlan
    {
        // Never hard-delete: issued subscriptions reference the plan. Deactivate only.
        return $this->plans->deactivate($plan);
    }
}
