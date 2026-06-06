<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Contracts;

use App\Models\Subscription;

interface SubscriptionRepositoryInterface
{
    public function activeForUser(string $userId): ?Subscription;

    /**
     * Row-locked active subscription for safe balance mutation inside a transaction.
     */
    public function lockActiveForUser(string $userId): ?Subscription;

    /**
     * Deduct minutes from a time-balance subscription and write an immutable ledger row.
     * No-op for date-bound (remaining_minutes === null) plans. Returns the balance after.
     */
    public function deduct(Subscription $subscription, int $minutes, ?string $visitId, string $userId): int;
}
