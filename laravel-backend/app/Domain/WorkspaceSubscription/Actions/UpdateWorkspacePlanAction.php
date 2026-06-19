<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Domain\WorkspaceSubscription\Data\WorkspacePlanData;
use App\Models\WorkspacePlan;

final readonly class UpdateWorkspacePlanAction
{
    public function __construct(private WorkspacePlanRepositoryInterface $plans) {}

    public function handle(WorkspacePlan $plan, WorkspacePlanData $data): WorkspacePlan
    {
        // Editing a template never mutates already-issued subscriptions — those
        // keep their own snapshots.
        return $this->plans->update($plan, [
            'name' => $data->name,
            'included_minutes' => $data->includedMinutes,
            'duration_days' => $data->durationDays,
            'price_cents' => $data->priceCents,
            'currency' => $data->currency,
            'is_active' => $data->isActive,
        ]);
    }
}
