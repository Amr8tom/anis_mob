<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Repositories;

use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\SubscriptionLedger;

final class EloquentSubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function activeForUser(string $userId): ?Subscription
    {
        return Subscription::query()
            ->with('plan')
            ->where('user_id', $userId)
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->latest('started_at')
            ->first();
    }

    public function lockActiveForUser(string $userId): ?Subscription
    {
        return Subscription::query()
            ->where('user_id', $userId)
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->lockForUpdate()
            ->latest('started_at')
            ->first();
    }

    public function deduct(Subscription $subscription, int $minutes, ?string $visitId, string $userId): int
    {
        // Date-bound plan (no minute balance): nothing to deduct, no ledger entry.
        if ($subscription->remaining_minutes === null) {
            return 0;
        }

        $before = $subscription->remaining_minutes;
        $after = max(0, $before - $minutes);

        $subscription->update(['remaining_minutes' => $after]);

        SubscriptionLedger::create([
            'subscription_id' => $subscription->id,
            'visit_id' => $visitId,
            'user_id' => $userId,
            'change_minutes' => -($before - $after),
            'balance_after' => $after,
            'reason' => 'WORKSPACE_VISIT',
        ]);

        return $after;
    }
}
