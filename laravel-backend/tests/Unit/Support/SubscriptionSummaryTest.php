<?php

namespace Tests\Unit\Support;

use App\Models\Plan;
use App\Models\Subscription;
use App\Support\SubscriptionSummary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_subscription()
    {
        $summary = SubscriptionSummary::fromSubscription(null);

        $this->assertEquals('free', $summary->tier);
        $this->assertEquals(0, $summary->daysRemaining);
        $this->assertEquals(0, $summary->totalDays);
        $this->assertEquals(0, $summary->remainingMinutes);
    }

    public function test_free_subscription()
    {
        $plan = Plan::factory()->create(['tier' => 'FREE', 'duration_days' => 30]);
        $subscription = Subscription::factory()->create([
            'plan_id' => $plan->id,
            'expires_at' => now()->addDays(15),
            'remaining_minutes' => 0,
        ]);

        $summary = SubscriptionSummary::fromSubscription($subscription);

        $this->assertEquals('free', $summary->tier);
        $this->assertEquals(15, $summary->daysRemaining);
        $this->assertEquals(30, $summary->totalDays);
        $this->assertEquals(0, $summary->remainingMinutes);
    }

    public function test_silver_subscription()
    {
        $plan = Plan::factory()->create(['tier' => 'SILVER', 'duration_days' => 30]);
        $subscription = Subscription::factory()->create([
            'plan_id' => $plan->id,
            'expires_at' => now()->addDays(10),
            'remaining_minutes' => 600,
        ]);

        $summary = SubscriptionSummary::fromSubscription($subscription);

        $this->assertEquals('silver', $summary->tier);
        $this->assertEquals(10, $summary->daysRemaining);
        $this->assertEquals(30, $summary->totalDays);
        $this->assertEquals(600, $summary->remainingMinutes);
    }

    public function test_gold_subscription()
    {
        $plan = Plan::factory()->create(['tier' => 'GOLD', 'duration_days' => 60]);
        $subscription = Subscription::factory()->create([
            'plan_id' => $plan->id,
            'expires_at' => now()->addDays(20),
            'remaining_minutes' => 1200,
        ]);

        $summary = SubscriptionSummary::fromSubscription($subscription);

        $this->assertEquals('gold', $summary->tier);
        $this->assertEquals(20, $summary->daysRemaining);
        $this->assertEquals(60, $summary->totalDays);
        $this->assertEquals(1200, $summary->remainingMinutes);
    }

    public function test_expired_subscription()
    {
        $plan = Plan::factory()->create(['tier' => 'SILVER', 'duration_days' => 30]);
        $subscription = Subscription::factory()->create([
            'plan_id' => $plan->id,
            'expires_at' => now()->subDays(5),
            'remaining_minutes' => 0,
        ]);

        $summary = SubscriptionSummary::fromSubscription($subscription);

        $this->assertEquals('silver', $summary->tier);
        $this->assertEquals(0, $summary->daysRemaining); // Max(0, negative) = 0
        $this->assertEquals(30, $summary->totalDays);
        $this->assertEquals(0, $summary->remainingMinutes);
    }
}
