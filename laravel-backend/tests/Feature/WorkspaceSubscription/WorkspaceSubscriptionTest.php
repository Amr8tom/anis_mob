<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspaceSubscription;

use App\Enums\BillingSource;
use App\Enums\UserRole;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceSubscriptionCodeStatus;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceSubscriptionCode;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WorkspaceSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private function paidWorkspace(float $multiplier = 2.0): Workspace
    {
        return Workspace::factory()->create([
            'hour_multiplier' => $multiplier,
            'day_calculation_hours' => 8,
            'open_time' => null,   // skip the open-hours window in tests
            'close_time' => null,
        ]);
    }

    public function test_redeeming_wsp_code_creates_active_workspace_subscription(): void
    {
        $user = User::factory()->create();
        $workspace = $this->paidWorkspace();
        $plan = WorkspacePlan::factory()->create(['workspace_id' => $workspace->id, 'included_minutes' => 1200, 'duration_days' => 30]);
        $code = WorkspaceSubscriptionCode::factory()->create([
            'workspace_id' => $workspace->id,
            'workspace_plan_id' => $plan->id,
            'code' => 'WSP-AAAA-BBBB',
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/subscriptions/activate', ['code' => 'WSP-AAAA-BBBB'])
            ->assertOk()
            ->assertJsonPath('data.scope', 'workspace')
            ->assertJsonPath('data.remainingMinutes', 1200);

        $this->assertSame(WorkspaceSubscriptionCodeStatus::REDEEMED, $code->refresh()->status);
        $this->assertDatabaseHas('workspace_subscriptions', [
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'status' => 'ACTIVE',
            'remaining_minutes' => 1200,
        ]);
        $this->assertDatabaseHas('workspace_subscription_ledgers', [
            'user_id' => $user->id,
            'reason' => 'ACTIVATION',
            'change_minutes' => 1200,
        ]);
    }

    public function test_cannot_redeem_when_already_has_active_subscription_here(): void
    {
        $user = User::factory()->create();
        $workspace = $this->paidWorkspace();
        $plan = WorkspacePlan::factory()->create(['workspace_id' => $workspace->id]);
        WorkspaceSubscription::factory()->create(['workspace_id' => $workspace->id, 'user_id' => $user->id]);
        WorkspaceSubscriptionCode::factory()->create([
            'workspace_id' => $workspace->id, 'workspace_plan_id' => $plan->id, 'code' => 'WSP-CCCC-DDDD',
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/subscriptions/activate', ['code' => 'WSP-CCCC-DDDD'])
            ->assertStatus(409);
    }

    public function test_workspace_owner_can_generate_and_revoke_unused_codes(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = $this->paidWorkspace();
        $workspace->update(['owner_id' => $owner->id]);
        $plan = WorkspacePlan::factory()->create(['workspace_id' => $workspace->id]);

        $this->actingAs($owner)
            ->post(route('workspace.subscriptions.codes.generate'), [
                'workspace_plan_id' => $plan->id,
                'count' => 2,
            ], ['Idempotency-Key' => 'generate-workspace-codes'])
            ->assertRedirect()
            ->assertSessionHas('generated_workspace_codes', fn (array $codes): bool => count($codes) === 2);

        $code = WorkspaceSubscriptionCode::query()
            ->where('workspace_id', $workspace->id)
            ->firstOrFail();

        $this->actingAs($owner)
            ->post(route('workspace.subscriptions.codes.revoke', $code))
            ->assertRedirect();

        $this->assertSame(WorkspaceSubscriptionCodeStatus::REVOKED, $code->refresh()->status);
        $this->assertNotNull($code->revoked_at);
    }

    public function test_checkin_prefers_workspace_subscription(): void
    {
        $user = User::factory()->create();
        $workspace = $this->paidWorkspace();
        $sub = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id, 'user_id' => $user->id, 'remaining_minutes' => 600, 'total_minutes' => 600,
        ]);

        $this->actingAs($user)
            ->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token])
            ->assertCreated()
            ->assertJsonPath('data.billing_source', 'WORKSPACE_SUBSCRIPTION');

        $this->assertDatabaseHas('workspace_visits', [
            'user_id' => $user->id,
            'workspace_subscription_id' => $sub->id,
            'subscription_id' => null,
            'billing_source' => 'WORKSPACE_SUBSCRIPTION',
        ]);
    }

    public function test_checkout_deducts_workspace_sub_at_multiplier_one(): void
    {
        $user = User::factory()->create();
        $workspace = $this->paidWorkspace(2.0);   // global would be 2x; workspace must stay 1x
        $sub = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id, 'user_id' => $user->id, 'remaining_minutes' => 600, 'total_minutes' => 600,
        ]);
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'workspace_subscription_id' => $sub->id,
            'subscription_id' => null,
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION,
            'check_in_at' => now()->subMinutes(60),
        ]);

        $this->actingAs($user)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")
            ->assertOk();

        $visit->refresh();
        $this->assertSame(60, $visit->billable_minutes);
        $this->assertSame(60, $visit->deducted_minutes);   // 1.0 multiplier, NOT 120
        $this->assertEquals(1.0, $visit->hour_multiplier_applied);
        $this->assertSame(540, $sub->refresh()->remaining_minutes);
        $this->assertDatabaseHas('workspace_subscription_ledgers', [
            'workspace_subscription_id' => $sub->id,
            'reason' => 'WORKSPACE_VISIT',
            'change_minutes' => -60,
        ]);
    }

    public function test_checkout_exhausts_subscription_when_balance_hits_zero(): void
    {
        $user = User::factory()->create();
        $workspace = $this->paidWorkspace(1.0);
        $sub = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id, 'user_id' => $user->id, 'remaining_minutes' => 40, 'total_minutes' => 600,
        ]);
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'workspace_subscription_id' => $sub->id,
            'subscription_id' => null,
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION,
            'check_in_at' => now()->subMinutes(60),
        ]);

        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")->assertOk();

        $sub->refresh();
        $this->assertSame(0, $sub->remaining_minutes);
        $this->assertSame(WorkspaceSubscriptionStatus::EXHAUSTED, $sub->status);
        $this->assertSame(40, $visit->refresh()->deducted_minutes);   // clamped at balance
    }

    public function test_expire_command_forfeits_and_skips_open_visits(): void
    {
        $workspace = $this->paidWorkspace();
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $expiredIdle = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id, 'user_id' => $userA->id,
            'remaining_minutes' => 120, 'expires_at' => now()->subHour(),
        ]);
        $expiredFunding = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id, 'user_id' => $userB->id,
            'remaining_minutes' => 120, 'expires_at' => now()->subHour(),
        ]);
        WorkspaceVisit::factory()->create([
            'user_id' => $userB->id, 'workspace_id' => $workspace->id,
            'workspace_subscription_id' => $expiredFunding->id,
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION,
            'status' => VisitStatus::CHECKED_IN,
        ]);

        $this->artisan('workspace-subscriptions:expire')
            ->expectsOutputToContain('Expired 1 workspace subscriptions.')
            ->assertSuccessful();

        $this->assertSame(WorkspaceSubscriptionStatus::EXPIRED, $expiredIdle->refresh()->status);
        $this->assertSame(0, $expiredIdle->remaining_minutes);
        $this->assertSame(WorkspaceSubscriptionStatus::ACTIVE, $expiredFunding->refresh()->status);   // left for checkout
    }

    public function test_subscription_expired_during_visit_is_finalized_after_checkout(): void
    {
        $user = User::factory()->create();
        $workspace = $this->paidWorkspace();
        $sub = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'remaining_minutes' => 120,
            'expires_at' => now()->subMinute(),
        ]);
        $visit = WorkspaceVisit::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'workspace_subscription_id' => $sub->id,
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION,
            'check_in_at' => now()->subMinutes(30),
        ]);

        $this->actingAs($user)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")
            ->assertOk();

        $this->assertSame(WorkspaceSubscriptionStatus::EXPIRED, $sub->fresh()->status);
        $this->assertSame(0, $sub->fresh()->remaining_minutes);
    }
}
