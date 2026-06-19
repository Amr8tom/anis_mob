<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Subscription;
use App\Models\User;

/**
 * Derives the subscription figures the Flutter home/profile screens show.
 * Global remaining-days bar is plan-driven and independent of any workspace.
 */
final readonly class SubscriptionSummary
{
    public function __construct(
        public string $tier,
        public int $daysRemaining,
        public int $totalDays,
        public int $remainingMinutes,
    ) {}

    public static function forUser(User $user): self
    {
        $subscription = $user->relationLoaded('activeSubscription')
            ? $user->activeSubscription
            : $user->activeSubscription()->with('plan')->first();

        return self::fromSubscription($subscription);
    }

    public static function fromSubscription(?Subscription $subscription): self
    {
        if ($subscription === null) {
            return new self('free', 0, 0, 0);
        }

        $plan = $subscription->plan;
        $tier = strtolower($plan?->tier->value ?? 'free');
        $totalDays = (int) ($plan?->duration_days ?? 0);

        $daysRemaining = 0;
        if ($subscription->expires_at !== null) {
            $daysRemaining = max(0, (int) ceil(now()->diffInDays($subscription->expires_at, false)));
        }

        $remainingMinutes = (int) ($subscription->remaining_minutes ?? 0);

        return new self($tier, $daysRemaining, $totalDays, $remainingMinutes);
    }
}
