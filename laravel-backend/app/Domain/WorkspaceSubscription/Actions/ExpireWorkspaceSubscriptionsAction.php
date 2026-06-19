<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Enums\WorkspaceSubscriptionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Batch expiry for the scheduler. Expires ACTIVE subscriptions whose expires_at
 * has passed — but NEVER one that is currently funding an open visit (R12); that
 * one is expired right after checkout instead.
 */
final readonly class ExpireWorkspaceSubscriptionsAction
{
    public function __construct(
        private WorkspaceSubscriptionRepositoryInterface $subscriptions,
        private ExpireWorkspaceSubscriptionAction $expireOne,
    ) {}

    public function handle(): int
    {
        $count = 0;

        $this->subscriptions->dueForExpiryQuery()
            ->select('id')
            ->lazyById(200)
            ->each(function ($candidate) use (&$count): void {
                DB::transaction(function () use ($candidate, &$count): void {
                    $locked = $this->subscriptions->lockById($candidate->id);
                    if (
                        $locked === null
                        || $locked->status !== WorkspaceSubscriptionStatus::ACTIVE
                        || $locked->expires_at === null
                        || $locked->expires_at->isFuture()
                    ) {
                        return;
                    }

                    $before = $locked->status;
                    $expired = $this->expireOne->handle($locked);
                    if ($before !== $expired->status) {
                        $count++;
                    }
                });
            });

        Log::info('workspace_subscription.batch_expired', ['count' => $count]);

        return $count;
    }
}
