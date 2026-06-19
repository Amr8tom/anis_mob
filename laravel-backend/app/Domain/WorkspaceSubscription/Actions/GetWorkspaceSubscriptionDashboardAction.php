<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionCodeRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;

/**
 * Builds everything the owner "الاشتراكات الخاصة" tab needs in one place.
 */
final readonly class GetWorkspaceSubscriptionDashboardAction
{
    public function __construct(
        private WorkspacePlanRepositoryInterface $plans,
        private WorkspaceSubscriptionRepositoryInterface $subscriptions,
        private WorkspaceSubscriptionCodeRepositoryInterface $codes,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function handle(string $workspaceId, int $perPage = 15): array
    {
        $expiringSoon = $this->subscriptions->expiringWithinDays($workspaceId, 3);

        return [
            'plans' => $this->plans->allForWorkspace($workspaceId),
            'subscriptions' => $this->subscriptions->paginateForWorkspace($workspaceId, $perPage),
            'codes' => $this->codes->paginateForWorkspace($workspaceId, $perPage),
            'expiring_soon' => $expiringSoon,
            'metrics' => [
                'active_count' => $this->subscriptions->activeCountForWorkspace($workspaceId),
                'expiring_count' => $expiringSoon->count(),
                'hours_sold' => $this->subscriptions->hoursSoldForWorkspace($workspaceId),
                'plan_count' => $this->plans->allForWorkspace($workspaceId)->count(),
            ],
        ];
    }
}
