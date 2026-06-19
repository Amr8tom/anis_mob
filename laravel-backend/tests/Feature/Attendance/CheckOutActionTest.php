<?php

namespace Tests\Feature\Attendance;

use App\Domain\Attendance\Actions\CheckOutAction;
use App\Enums\BillingSource;
use App\Enums\SubscriptionStatus;
use App\Enums\VisitStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckOutActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Workspace $workspace;

    private Subscription $subscription;

    private CheckOutAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->workspace = Workspace::factory()->create(['hour_multiplier' => 1.0, 'day_calculation_hours' => 8]);

        $plan = Plan::factory()->create(['tier' => 'SILVER', 'included_minutes' => 600]);
        $this->subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'remaining_minutes' => 600,
            'active_flag' => 1,
        ]);

        $this->action = app(CheckOutAction::class);
    }

    public function test_checkout_deducts_from_captured_subscription_even_if_expired_during_visit(): void
    {
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(60),
            'active_flag' => 1,
        ]);

        // Expire the subscription
        $this->subscription->update(['status' => SubscriptionStatus::EXPIRED, 'active_flag' => null]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(VisitStatus::CHECKED_OUT, $result->status);
        $this->assertEquals(60, $result->deducted_minutes);

        $this->subscription->refresh();
        $this->assertEquals(540, $this->subscription->remaining_minutes);
    }

    public function test_checkout_deducts_from_captured_subscription_even_if_replaced(): void
    {
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id, // old sub
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(60),
            'active_flag' => 1,
        ]);

        // User gets a new subscription
        $this->subscription->update(['status' => SubscriptionStatus::EXPIRED, 'active_flag' => null]);
        $newSub = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'plan_id' => $this->subscription->plan_id,
            'status' => SubscriptionStatus::ACTIVE,
            'remaining_minutes' => 600,
            'active_flag' => 1,
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(60, $result->deducted_minutes);

        $this->subscription->refresh();
        $this->assertEquals(540, $this->subscription->remaining_minutes); // Deducted from old

        $newSub->refresh();
        $this->assertEquals(600, $newSub->remaining_minutes); // New sub untouched
    }

    public function test_checkout_handles_insufficient_minutes(): void
    {
        $this->subscription->update(['remaining_minutes' => 30]);

        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(60),
            'active_flag' => 1,
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(30, $result->deducted_minutes);
        $this->assertEquals(30, $result->billable_minutes);

        $this->subscription->refresh();
        $this->assertEquals(0, $this->subscription->remaining_minutes); // Clamped to 0
    }

    public function test_fractional_multiplier_never_funds_more_than_visit_duration(): void
    {
        $this->workspace->update(['hour_multiplier' => 0.5]);
        $this->subscription->update(['remaining_minutes' => 600]);
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'check_in_at' => now()->subMinutes(60),
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertSame(60, $result->billable_minutes);
        $this->assertSame(30, $result->deducted_minutes);
    }

    public function test_wallet_is_not_deducted_when_it_cannot_fund_one_real_minute(): void
    {
        $this->workspace->update(['hour_multiplier' => 2]);
        $this->subscription->update(['remaining_minutes' => 1]);
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'check_in_at' => now()->subMinutes(10),
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertSame(0, $result->billable_minutes);
        $this->assertSame(0, $result->deducted_minutes);
        $this->assertSame(1, $this->subscription->fresh()->remaining_minutes);
    }

    public function test_checkout_does_not_deduct_if_workspace_was_free(): void
    {
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => null, // Free workspace checkin
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(60),
            'active_flag' => 1,
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(60, $result->duration_minutes);
        $this->assertEquals(0, $result->deducted_minutes);

        $this->subscription->refresh();
        $this->assertEquals(600, $this->subscription->remaining_minutes); // Untouched
    }

    public function test_checkout_caps_billable_minutes_at_workspace_daily_limit(): void
    {
        // 1-hour daily cap: a 2-hour visit bills only 60 minutes.
        $this->workspace->update(['day_calculation_hours' => 1]);

        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(120),
            'active_flag' => 1,
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(VisitStatus::CHECKED_OUT, $result->status);
        $this->assertEquals(120, $result->duration_minutes); // raw attendance preserved
        $this->assertEquals(60, $result->billable_minutes);   // capped
    }

    public function test_checkout_under_five_minutes_is_free(): void
    {
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(4),
            'active_flag' => 1,
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(4, $result->duration_minutes);
        $this->assertEquals(0, $result->billable_minutes);
        $this->assertEquals(0, $result->deducted_minutes);
    }

    public function test_checkout_between_five_and_sixty_minutes_bills_sixty_minutes(): void
    {
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(15),
            'active_flag' => 1,
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(15, $result->duration_minutes);
        $this->assertEquals(60, $result->billable_minutes);
        $this->assertEquals(60, $result->deducted_minutes);
    }

    public function test_checkout_above_sixty_minutes_bills_real_time(): void
    {
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $this->user->id,
            'subscription_id' => $this->subscription->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(65),
            'active_flag' => 1,
        ]);

        $result = $this->action->handle($visit->id, $this->user->id);

        $this->assertEquals(65, $result->duration_minutes);
        $this->assertEquals(65, $result->billable_minutes);
        $this->assertEquals(65, $result->deducted_minutes);
    }
}
