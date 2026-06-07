<?php

declare(strict_types=1);

namespace Tests\Feature\Attendance;

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

final class AutoCheckOutTest extends TestCase
{
    use RefreshDatabase;

    private function createSilverUser(): User
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
            'remaining_minutes' => 7200,
            'active_flag' => 1,
        ]);

        return $user;
    }

    public function test_auto_checkout_closes_stale_visits(): void
    {
        $user = $this->createSilverUser();
        $workspace = Workspace::factory()->create([
            'close_time' => now()->subMinutes(10)->format('H:i'), // closed 10 mins ago
        ]);
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subHours(2),
        ]);

        $this->artisan('visits:auto-checkout')
            ->expectsOutputToContain('Auto-checked-out 1 visits.')
            ->assertSuccessful();

        $this->assertSame(VisitStatus::CHECKED_OUT, $visit->refresh()->status);
        $this->assertNotNull($visit->check_out_at);
        $this->assertSame(120, $visit->duration_minutes);
    }

    public function test_auto_checkout_ignores_open_workspaces(): void
    {
        $user = $this->createSilverUser();
        $workspace = Workspace::factory()->create([
            'close_time' => now()->addHours(2)->format('H:i'), // closes in 2 hours
        ]);
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subHour(),
        ]);

        $this->artisan('visits:auto-checkout')
            ->expectsOutputToContain('Auto-checked-out 0 visits.')
            ->assertSuccessful();

        $this->assertSame(VisitStatus::CHECKED_IN, $visit->refresh()->status);
        $this->assertNull($visit->check_out_at);
    }

    public function test_auto_checkout_is_idempotent(): void
    {
        $user = $this->createSilverUser();
        $workspace = Workspace::factory()->create([
            'close_time' => now()->subMinutes(10)->format('H:i'),
        ]);
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'status' => VisitStatus::CHECKED_OUT,
            'check_in_at' => now()->subHours(2),
            'check_out_at' => now()->subHour(),
            'duration_minutes' => 60,
            'billable_minutes' => 60,
            'deducted_minutes' => 60,
            'hour_multiplier_applied' => 1.0,
        ]);

        $this->artisan('visits:auto-checkout')
            ->expectsOutputToContain('Auto-checked-out 0 visits.')
            ->assertSuccessful();
    }
}
