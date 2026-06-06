<?php

declare(strict_types=1);

namespace Tests\Feature\Subscription;

use App\Enums\PlanTier;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PlanSubscriptionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_plans_are_public_and_db_driven(): void
    {
        Plan::factory()->create(['name' => 'Gold Monthly', 'tier' => PlanTier::GOLD, 'is_active' => true]);
        Plan::factory()->create(['is_active' => false]);

        $this->getJson('/api/v1/plans')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'tier', 'price', 'billingType']]]);
    }

    public function test_current_subscription_requires_authentication(): void
    {
        $this->getJson('/api/v1/subscriptions/current')->assertStatus(401);
    }

    public function test_current_subscription_returns_active(): void
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create(['tier' => PlanTier::SILVER, 'duration_days' => 30]);
        Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'expires_at' => now()->addDays(10),
        ]);

        $this->actingAs($user)
            ->getJson('/api/v1/subscriptions/current')
            ->assertOk()
            ->assertJsonPath('data.subscriptionType', 'silver')
            ->assertJsonPath('data.subscriptionDaysRemaining', 10);
    }

    public function test_current_subscription_null_when_none(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/v1/subscriptions/current')
            ->assertOk()
            ->assertJsonPath('data', null);
    }
}
