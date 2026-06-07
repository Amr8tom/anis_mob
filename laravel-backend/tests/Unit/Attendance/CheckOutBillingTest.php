<?php

declare(strict_types=1);

namespace Tests\Unit\Attendance;

use App\Domain\Attendance\Actions\CheckOutAction;
use App\Enums\PlanTier;
use App\Enums\SubscriptionStatus;
use App\Enums\VisitStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CheckOutBillingTest extends TestCase
{
    use RefreshDatabase;

    private CheckOutAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = resolve(CheckOutAction::class);
    }

    private function createSilverUser(int $remainingMinutes = 7200): User
    {
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Silver',
            'tier' => PlanTier::SILVER,
            'price_cents' => 170000,
            'currency' => 'EGP',
            'included_minutes' => 7200,
            'duration_days' => 30,
            'is_active' => true,
        ]);
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'started_at' => now(),
            'expires_at' => now()->addDays(30),
            'remaining_minutes' => $remainingMinutes,
            'active_flag' => 1,
        ]);

        return $user;
    }

    public function test_default_multiplier_deducts_raw_minutes(): void
    {
        $user = $this->createSilverUser();
        $workspace = Workspace::factory()->create([
            'hour_multiplier' => 1.00,
            'day_calculation_hours' => 8,
        ]);
        $subscription = $user->subscriptions()->first();
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(90),
        ]);

        $updatedVisit = $this->action->handle($visit->id, $user->id);

        $this->assertSame(90, $updatedVisit->duration_minutes);
        $this->assertSame(90, $updatedVisit->billable_minutes);
        $this->assertSame(90, $updatedVisit->deducted_minutes);
        $this->assertEquals(1.00, $updatedVisit->hour_multiplier_applied);
        $this->assertSame(7110, (int) $subscription->refresh()->remaining_minutes);
    }

    public function test_2x_multiplier_doubles_deduction(): void
    {
        $user = $this->createSilverUser();
        $workspace = Workspace::factory()->create([
            'hour_multiplier' => 2.00,
            'day_calculation_hours' => 8,
        ]);
        $subscription = $user->subscriptions()->first();
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(90),
        ]);

        $updatedVisit = $this->action->handle($visit->id, $user->id);

        $this->assertSame(90, $updatedVisit->duration_minutes);
        $this->assertSame(90, $updatedVisit->billable_minutes);
        $this->assertSame(180, $updatedVisit->deducted_minutes);
        $this->assertEquals(2.00, $updatedVisit->hour_multiplier_applied);
        $this->assertSame(7020, (int) $subscription->refresh()->remaining_minutes);
    }

    public function test_daily_cap_limits_billable_minutes(): void
    {
        $user = $this->createSilverUser();
        $workspace = Workspace::factory()->create([
            'hour_multiplier' => 2.00,
            'day_calculation_hours' => 6, // 6 hours cap = 360 real minutes max. Max deducted = 720 subscription minutes.
        ]);
        $subscription = $user->subscriptions()->first();
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subHours(7), // 7 hours real sitting time (420 minutes)
        ]);

        $updatedVisit = $this->action->handle($visit->id, $user->id);

        $this->assertSame(420, $updatedVisit->duration_minutes);
        $this->assertSame(360, $updatedVisit->billable_minutes); // capped at 6 hours
        $this->assertSame(720, $updatedVisit->deducted_minutes); // 360 * 2.0 = 720
        $this->assertEquals(2.00, $updatedVisit->hour_multiplier_applied);
        $this->assertSame(6480, (int) $subscription->refresh()->remaining_minutes); // 7200 - 720 = 6480
    }

    public function test_daily_cap_across_multiple_visits(): void
    {
        $user = $this->createSilverUser(6840);
        $workspace = Workspace::factory()->create([
            'hour_multiplier' => 1.50,
            'day_calculation_hours' => 6, // 360 real minutes cap = 540 deducted minutes cap
        ]);
        $subscription = $user->subscriptions()->first();

        // First visit: 4 hours (240 minutes real). Deducted = 360 subscription minutes.
        $visit1 = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subHours(5),
            'check_out_at' => now()->subHours(1),
            'duration_minutes' => 240,
            'billable_minutes' => 240,
            'deducted_minutes' => 360,
            'hour_multiplier_applied' => 1.50,
            'status' => VisitStatus::CHECKED_OUT,
            'active_flag' => null,
        ]);

        // Second visit: 3 hours (180 minutes real). Remaining real cap is 120 minutes (180 subscription minutes remaining).
        $visit2 = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(180),
        ]);

        $updatedVisit2 = $this->action->handle($visit2->id, $user->id);

        $this->assertSame(180, $updatedVisit2->duration_minutes);
        $this->assertSame(120, $updatedVisit2->billable_minutes); // capped remaining real minutes
        $this->assertSame(180, $updatedVisit2->deducted_minutes); // 120 * 1.5 = 180
        $this->assertSame(6660, (int) $subscription->refresh()->remaining_minutes); // 7200 - 360 (first) - 180 (second) = 6660
    }

    public function test_already_capped_visit_is_free(): void
    {
        $user = $this->createSilverUser();
        $workspace = Workspace::factory()->create([
            'hour_multiplier' => 2.00,
            'day_calculation_hours' => 6, // 360 real cap, 720 deducted cap
        ]);
        $subscription = $user->subscriptions()->first();

        // Already fully capped today
        WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subHours(8),
            'check_out_at' => now()->subHours(2),
            'duration_minutes' => 360,
            'billable_minutes' => 360,
            'deducted_minutes' => 720,
            'hour_multiplier_applied' => 2.00,
            'status' => VisitStatus::CHECKED_OUT,
            'active_flag' => null,
        ]);

        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(60),
        ]);

        $updatedVisit = $this->action->handle($visit->id, $user->id);

        $this->assertSame(60, $updatedVisit->duration_minutes);
        $this->assertSame(0, $updatedVisit->billable_minutes);
        $this->assertSame(0, $updatedVisit->deducted_minutes);
        $this->assertSame(7200, (int) $subscription->refresh()->remaining_minutes); // no extra deduction
    }

    public function test_free_workspace_does_not_require_subscription(): void
    {
        $user = User::factory()->create(); // No subscription at all
        $workspace = Workspace::factory()->create([
            'hour_multiplier' => 0.00, // FREE workspace!
            'day_calculation_hours' => 8,
        ]);

        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => null,
            'check_in_at' => now()->subMinutes(120),
        ]);

        $updatedVisit = $this->action->handle($visit->id, $user->id);

        $this->assertSame(120, $updatedVisit->duration_minutes);
        $this->assertSame(120, $updatedVisit->billable_minutes);
        $this->assertSame(0, $updatedVisit->deducted_minutes);
        $this->assertEquals(0.00, $updatedVisit->hour_multiplier_applied);
    }
}
