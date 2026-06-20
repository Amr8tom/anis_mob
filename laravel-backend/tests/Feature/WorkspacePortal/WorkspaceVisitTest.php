<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Enums\BillingSource;
use App\Enums\PlanTier;
use App\Enums\RoomReservationStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\UserRole;
use App\Models\Plan;
use App\Models\RoomReservation;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceRoom;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WorkspaceVisitTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_register_a_free_walk_in_visit(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.store'), [
                'phone_number' => '01011112222',
                'name' => 'Walk In Visitor',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $walkIn = WorkspaceWalkIn::where('phone_number', '01011112222')->firstOrFail();

        $this->assertDatabaseMissing('users', ['phone_number' => '01011112222']);
        $this->assertDatabaseHas('workspace_visits', [
            'user_id' => null,
            'walk_in_id' => $walkIn->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => null,
            'plan_tier_snapshot' => PlanTier::FREE->value,
        ]);
    }

    public function test_owner_can_register_a_free_existing_app_user(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $appUser = User::factory()->create([
            'phone_number' => '01033334444',
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.store'), [
                'phone_number' => $appUser->phone_number,
                'name' => 'Ignored Name',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workspace_visits', [
            'user_id' => $appUser->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => null,
            'billing_source' => BillingSource::FREE->value,
            'plan_tier_snapshot' => PlanTier::FREE->value,
        ]);

        $visit = WorkspaceVisit::where('user_id', $appUser->id)->firstOrFail();
        $this->travel(20)->minutes();

        $this->actingAs($owner)
            ->post(route('workspace.visits.checkout', $visit))
            ->assertSessionHasNoErrors();

        $this->assertSame(20, $visit->fresh()->duration_minutes);
        $this->assertSame(60, $visit->fresh()->billable_minutes);
        $this->assertSame(0, $visit->fresh()->deducted_minutes);
    }

    public function test_owner_can_register_workspace_subscription_user_without_password(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id, 'hour_multiplier' => 2]);
        $appUser = User::factory()->create(['phone_number' => '01033335555']);
        $subscription = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $appUser->id,
            'remaining_minutes' => 120,
            'total_minutes' => 120,
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.store'), ['phone_number' => $appUser->phone_number])
            ->assertSessionHasNoErrors()
            ->assertSessionMissing('paid_visitor_verification');

        $visit = WorkspaceVisit::where('user_id', $appUser->id)->firstOrFail();
        $this->assertSame(BillingSource::WORKSPACE_SUBSCRIPTION, $visit->billing_source);

        $this->travel(20)->minutes();
        $this->actingAs($owner)
            ->post(route('workspace.visits.checkout', $visit))
            ->assertSessionHasNoErrors();

        $this->assertSame(20, $visit->fresh()->duration_minutes);
        $this->assertSame(60, $subscription->fresh()->remaining_minutes);
        $this->assertSame(60, $visit->fresh()->deducted_minutes);
    }

    public function test_paid_user_must_confirm_with_password_before_owner_check_in(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $appUser = User::factory()->create(['phone_number' => '01033334445']);
        $plan = Plan::factory()->create(['tier' => PlanTier::SILVER]);
        $subscription = Subscription::factory()->create([
            'user_id' => $appUser->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'active_flag' => 1,
            'remaining_minutes' => 15,
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.store'), ['phone_number' => $appUser->phone_number])
            ->assertSessionHas('paid_visitor_verification');

        $this->assertDatabaseMissing('workspace_visits', [
            'user_id' => $appUser->id,
            'workspace_id' => $workspace->id,
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.store'), [
                'phone_number' => $appUser->phone_number,
                'visitor_password' => 'password',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('workspace_visits', [
            'user_id' => $appUser->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'plan_tier_snapshot' => PlanTier::SILVER->value,
        ]);
    }

    public function test_paid_visitor_verification_popup_contains_a_password_form(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        Workspace::factory()->create(['owner_id' => $owner->id]);

        $this->actingAs($owner)
            ->withSession([
                'paid_visitor_verification' => [
                    'phone_number' => '01033334445',
                    'visitor_name' => 'Paid Visitor',
                    'plan_tier' => PlanTier::SILVER->value,
                ],
            ])
            ->get(route('workspace.visits.index'))
            ->assertOk()
            ->assertSee('يؤكد الزائر وجوده بنفسه')
            ->assertSee('type="password"', false)
            ->assertSee('autocomplete="current-password"', false);
    }

    public function test_paid_user_wrong_password_does_not_create_visit(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $appUser = User::factory()->create(['phone_number' => '01033334448']);
        $plan = Plan::factory()->create(['tier' => PlanTier::GOLD]);
        Subscription::factory()->create([
            'user_id' => $appUser->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'active_flag' => 1,
            'remaining_minutes' => 60,
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.store'), [
                'phone_number' => $appUser->phone_number,
                'visitor_password' => 'wrong-password',
            ])
            ->assertSessionHasErrors('phone_number');

        $this->assertDatabaseMissing('workspace_visits', [
            'user_id' => $appUser->id,
            'workspace_id' => $workspace->id,
        ]);
    }

    public function test_owner_cannot_register_paid_user_with_less_than_fifteen_minutes(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $appUser = User::factory()->create(['phone_number' => '01033334446']);
        $plan = Plan::factory()->create(['tier' => PlanTier::GOLD]);
        Subscription::factory()->create([
            'user_id' => $appUser->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'active_flag' => 1,
            'remaining_minutes' => 14,
        ]);

        $this->actingAs($owner)
            ->from(route('workspace.visits.index'))
            ->post(route('workspace.visits.store'), ['phone_number' => $appUser->phone_number])
            ->assertRedirect(route('workspace.visits.index'))
            ->assertSessionHasErrors('phone_number');

        $this->assertDatabaseMissing('workspace_visits', [
            'user_id' => $appUser->id,
            'workspace_id' => $workspace->id,
        ]);
    }

    public function test_owner_can_check_out_registered_paid_user_and_deduct_balance(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id, 'hour_multiplier' => 1]);
        $appUser = User::factory()->create(['phone_number' => '01033334447']);
        $plan = Plan::factory()->create(['tier' => PlanTier::GOLD]);
        $subscription = Subscription::factory()->create([
            'user_id' => $appUser->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'active_flag' => 1,
            'remaining_minutes' => 60,
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.store'), [
                'phone_number' => $appUser->phone_number,
                'visitor_password' => 'password',
            ])
            ->assertSessionHasNoErrors();

        $visit = WorkspaceVisit::where('user_id', $appUser->id)->firstOrFail();
        $this->travel(20)->minutes();

        $this->actingAs($owner)
            ->post(route('workspace.visits.checkout', $visit))
            ->assertSessionHasNoErrors();

        $this->assertSame(20, $visit->fresh()->duration_minutes);
        $this->assertSame(0, $subscription->fresh()->remaining_minutes);
        $this->assertSame(60, $visit->fresh()->deducted_minutes);
    }

    public function test_recent_visits_filters_include_summary_and_revenue(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id, 'hour_multiplier' => 2]);
        $silverUser = User::factory()->create(['full_name' => 'Silver Visitor']);
        $freeUser = User::factory()->create(['full_name' => 'Free Visitor']);

        WorkspaceVisit::factory()->checkedOut(120)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $silverUser->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'plan_tier_snapshot' => PlanTier::SILVER,
        ]);
        WorkspaceVisit::factory()->checkedOut(60)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $freeUser->id,
            'plan_tier_snapshot' => PlanTier::FREE,
        ]);

        $this->actingAs($owner)
            ->get(route('workspace.visits.index', ['plan' => BillingSource::GLOBAL_SUBSCRIPTION->value]))
            ->assertOk()
            ->assertSee('Silver Visitor')
            ->assertDontSee('Free Visitor')
            ->assertViewHas('recentSummary', fn (array $summary): bool => $summary === [
                'total_visits' => 1,
                'total_minutes' => 120,
                'total_visitors' => 1,
                'total_revenue' => 60.0,
            ]);
    }

    public function test_clearing_recent_visits_only_hides_them_and_keeps_history(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $visit = WorkspaceVisit::factory()->checkedOut(90)->create(['workspace_id' => $workspace->id]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.clear-recent'))
            ->assertRedirect(route('workspace.visits.index'));

        $this->assertDatabaseHas('workspace_visits', ['id' => $visit->id]);
        $this->assertNotNull($workspace->fresh()->recent_visits_cleared_at);

        $this->actingAs($owner)
            ->get(route('workspace.visits.index'))
            ->assertViewHas('recentVisits', fn ($visits): bool => $visits->isEmpty());
    }

    public function test_clearing_recent_visits_hides_existing_room_reservations_until_new_ones_are_created(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $room = WorkspaceRoom::factory()->create(['workspace_id' => $workspace->id]);

        $oldReservation = RoomReservation::factory()->create([
            'workspace_id' => $workspace->id,
            'room_id' => $room->id,
            'client_name' => 'Old Room Guest',
            'client_phone' => '01011110000',
            'status' => RoomReservationStatus::RESERVED,
            'created_at' => now()->subMinutes(10),
            'updated_at' => now()->subMinutes(10),
        ]);

        $this->actingAs($owner)
            ->post(route('workspace.visits.clear-recent'))
            ->assertRedirect(route('workspace.visits.index'));

        WorkspaceVisit::factory()->checkedOut(30)->create(['workspace_id' => $workspace->id]);

        $this->actingAs($owner)
            ->get(route('workspace.visits.index'))
            ->assertOk()
            ->assertDontSee('Old Room Guest')
            ->assertViewHas('roomReservations', fn ($reservations): bool => $reservations->doesntContain('id', $oldReservation->id));

        RoomReservation::factory()->create([
            'workspace_id' => $workspace->id,
            'room_id' => $room->id,
            'client_name' => 'New Room Guest',
            'client_phone' => '01022220000',
            'status' => RoomReservationStatus::RESERVED,
            'created_at' => now()->addMinute(),
            'updated_at' => now()->addMinute(),
        ]);

        $this->actingAs($owner)
            ->get(route('workspace.visits.index'))
            ->assertOk()
            ->assertDontSee('Old Room Guest')
            ->assertSee('New Room Guest');
    }
}
