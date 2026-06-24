<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Subscription\Actions\CreateSubscriptionRefundAction;
use App\Enums\UserRole;
use App\Models\AdminAuditLog;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRefund;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceOwner;
use App\Models\WorkspaceOwnershipInvitation;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use LogicException;
use Tests\TestCase;

final class ProductionHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_in_retry_with_same_idempotency_key_creates_one_visit(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::factory()->create(['hour_multiplier' => 0, 'capacity' => 10]);
        $headers = ['Idempotency-Key' => 'check-in-123'];

        $first = $this->actingAs($user)->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token], $headers);
        $second = $this->actingAs($user)->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token], $headers);

        $first->assertCreated();
        $second->assertCreated()->assertExactJson($first->json());
        $this->assertDatabaseCount('workspace_visits', 1);
        $this->assertSame(1, $workspace->fresh()->active_visit_count);
    }

    public function test_atomic_capacity_reservation_rejects_second_user(): void
    {
        $workspace = Workspace::factory()->create(['hour_multiplier' => 0, 'capacity' => 1]);
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->actingAs($firstUser)->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token])->assertCreated();
        $this->actingAs($secondUser)->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token])->assertStatus(409);

        $this->assertSame(1, $workspace->fresh()->active_visit_count);
        $this->assertDatabaseCount('workspace_visits', 1);
    }

    public function test_admin_guard_and_explicit_permissions_are_enforced(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'admin_permissions' => [],
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));

        $this->actingAs($admin, 'admin')
            ->post(route('admin.plancodes.store'), [])
            ->assertForbidden();
    }

    public function test_admin_account_locks_after_repeated_bad_passwords(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'password' => Hash::make('correct-password'),
        ]);

        foreach (range(1, 5) as $attempt) {
            $this->post(route('admin.login'), [
                'phone_number' => $admin->phone_number,
                'password' => 'wrong-password',
            ]);
        }

        $this->assertTrue($admin->fresh()->admin_locked_until->isFuture());
    }

    public function test_historical_records_are_append_only_and_visits_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $log = AdminAuditLog::create(['admin_id' => $admin->id, 'action' => 'TEST']);
        $visit = WorkspaceVisit::factory()->checkedOut()->create();

        try {
            $log->update(['action' => 'CHANGED']);
            $this->fail('Audit log update should have been rejected.');
        } catch (LogicException) {
            $this->assertSame('TEST', $log->fresh()->action);
        }

        $this->expectException(LogicException::class);
        $visit->delete();
    }

    public function test_subscription_deduction_remains_atomic_with_ledger(): void
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create(['included_minutes' => 120]);
        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'remaining_minutes' => 120,
        ]);
        $workspace = Workspace::factory()->create(['hour_multiplier' => 1]);
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(65),
        ]);

        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")->assertOk();

        $this->assertSame(55, $subscription->fresh()->remaining_minutes);
        $this->assertDatabaseHas('subscription_ledgers', [
            'subscription_id' => $subscription->id,
            'visit_id' => $visit->id,
            'change_minutes' => -65,
            'balance_after' => 55,
        ]);
    }

    public function test_workspace_owner_can_accept_a_hashed_invitation_token(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $workspace = Workspace::factory()->create(['owner_id' => null]);
        $token = Str::random(48);
        WorkspaceOwnershipInvitation::create([
            'workspace_id' => $workspace->id,
            'phone_number' => '01077778888',
            'token_hash' => hash('sha256', $token),
            'invited_by_admin_id' => $admin->id,
            'expires_at' => now()->addDay(),
        ]);

        $this->post(route('workspace.invitations.accept', $token), [
            'full_name' => 'Invited Owner',
            'whatsapp_number' => '01077778888',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
        ])->assertRedirect(route('workspace.settings.edit'));

        $owner = WorkspaceOwner::where('phone_number', '01077778888')->firstOrFail();
        $this->assertSame('active', $owner->status);
        $this->assertSame($owner->id, $workspace->fresh()->workspace_owner_id);
        $this->assertDatabaseHas('workspace_ownership_changes', ['workspace_id' => $workspace->id, 'new_owner_id' => $workspace->owner_id]);
    }

    public function test_refund_adds_minutes_and_writes_immutable_records(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $subscription = Subscription::factory()->create(['remaining_minutes' => 10]);

        $refund = app(CreateSubscriptionRefundAction::class)->handle($subscription, null, $admin->id, 15, 'Support refund');

        $this->assertInstanceOf(SubscriptionRefund::class, $refund);
        $this->assertSame(25, $subscription->fresh()->remaining_minutes);
        $this->assertDatabaseHas('subscription_ledgers', [
            'subscription_id' => $subscription->id,
            'change_minutes' => 15,
            'balance_after' => 25,
            'reason' => 'REFUND',
        ]);
        $this->expectException(LogicException::class);
        $refund->delete();
    }
}
