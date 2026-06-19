<?php

declare(strict_types=1);

namespace Tests\Feature\Attendance;

use App\Domain\Attendance\Actions\OwnerCheckOutVisitAction;
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

final class CheckoutApprovalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: User, 1: Subscription}
     */
    private function silverUser(int $minutes = 600): array
    {
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Silver',
            'tier' => PlanTier::SILVER,
            'price_cents' => 170000,
            'currency' => 'EGP',
            'included_minutes' => $minutes,
            'duration_days' => 30,
            'is_active' => true,
        ]);
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'started_at' => now(),
            'expires_at' => now()->addDays(30),
            'remaining_minutes' => $minutes,
            'active_flag' => 1,
        ]);

        return [$user, $subscription];
    }

    private function visit(User $user, Workspace $workspace, ?Subscription $sub, PlanTier $tier, int $minutesAgo = 60): WorkspaceVisit
    {
        return WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $sub?->id,
            'plan_tier_snapshot' => $tier,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes($minutesAgo),
        ]);
    }

    public function test_paid_self_checkout_is_blocked_in_approval_mode(): void
    {
        [$user, $sub] = $this->silverUser();
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL', 'hour_multiplier' => 1.0]);
        $visit = $this->visit($user, $workspace, $sub, PlanTier::SILVER);

        $this->actingAs($user)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")
            ->assertStatus(403)
            ->assertJsonPath('success', false);

        $this->assertSame(VisitStatus::CHECKED_IN, $visit->refresh()->status);
    }

    public function test_request_checkout_marks_pending_without_deducting(): void
    {
        [$user, $sub] = $this->silverUser(600);
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL', 'hour_multiplier' => 1.0]);
        $visit = $this->visit($user, $workspace, $sub, PlanTier::SILVER);

        $this->actingAs($user)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.is_checkout_pending', true);

        $visit->refresh();
        $this->assertSame(VisitStatus::CHECKED_IN, $visit->status);
        $this->assertNotNull($visit->checkout_requested_at);
        // No balance change at request time.
        $this->assertSame(600, $sub->refresh()->remaining_minutes);
    }

    public function test_request_checkout_is_idempotent(): void
    {
        [$user, $sub] = $this->silverUser();
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL', 'hour_multiplier' => 1.0]);
        $visit = $this->visit($user, $workspace, $sub, PlanTier::SILVER);

        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")->assertOk();
        $firstRequestedAt = $visit->refresh()->checkout_requested_at;

        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")->assertOk();
        $this->assertEquals($firstRequestedAt, $visit->refresh()->checkout_requested_at);
    }

    public function test_free_visitor_checks_out_directly_even_in_approval_mode(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL', 'hour_multiplier' => 0.0]);
        $visit = $this->visit($user, $workspace, null, PlanTier::FREE);

        $this->actingAs($user)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")
            ->assertOk()
            ->assertJsonPath('data.check_out_time', fn ($v) => $v !== null);

        $this->assertSame(VisitStatus::CHECKED_OUT, $visit->refresh()->status);
    }

    public function test_paid_visitor_checks_out_directly_in_direct_mode(): void
    {
        [$user, $sub] = $this->silverUser();
        $workspace = Workspace::factory()->create(['checkout_mode' => 'DIRECT', 'hour_multiplier' => 1.0]);
        $visit = $this->visit($user, $workspace, $sub, PlanTier::SILVER);

        $this->actingAs($user)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")
            ->assertOk();

        $this->assertSame(VisitStatus::CHECKED_OUT, $visit->refresh()->status);
    }

    public function test_owner_approval_closes_request_and_deducts(): void
    {
        [$user, $sub] = $this->silverUser(600);
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL', 'hour_multiplier' => 1.0]);
        $visit = $this->visit($user, $workspace, $sub, PlanTier::SILVER, 60);

        // Visitor requests, owner approves.
        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")->assertOk();
        app(OwnerCheckOutVisitAction::class)->handle($workspace, $visit->id);

        $visit->refresh();
        $this->assertSame(VisitStatus::CHECKED_OUT, $visit->status);
        $this->assertSame(60, $visit->deducted_minutes);
        $this->assertSame(540, $sub->refresh()->remaining_minutes);
    }

    public function test_cancel_clears_pending_request(): void
    {
        [$user, $sub] = $this->silverUser();
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL', 'hour_multiplier' => 1.0]);
        $visit = $this->visit($user, $workspace, $sub, PlanTier::SILVER);

        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")->assertOk();
        $this->actingAs($user)->deleteJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")->assertOk();

        $this->assertNull($visit->refresh()->checkout_requested_at);
        $this->assertSame(VisitStatus::CHECKED_IN, $visit->status);
    }

    public function test_user_cannot_request_checkout_for_another_users_visit(): void
    {
        [$owner, $sub] = $this->silverUser();
        $other = User::factory()->create();
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL', 'hour_multiplier' => 1.0]);
        $visit = $this->visit($owner, $workspace, $sub, PlanTier::SILVER);

        $this->actingAs($other)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/request-checkout")
            ->assertStatus(404);
    }
}
