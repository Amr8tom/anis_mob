<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => Plan::factory(),
            'status' => SubscriptionStatus::ACTIVE,
            'started_at' => now(),
            'expires_at' => now()->addDays(30),
            'remaining_minutes' => 1200,
            'active_flag' => 1,   // enforces one ACTIVE subscription per user
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SubscriptionStatus::EXPIRED,
            'expires_at' => now()->subDay(),
            'remaining_minutes' => 0,
            'active_flag' => null,
        ]);
    }
}
