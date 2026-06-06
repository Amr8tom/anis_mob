<?php

declare(strict_types=1);

namespace Tests\Feature\Attendance;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AttendanceApiTest extends TestCase
{
    use RefreshDatabase;

    private function subscribedUser(int $remainingMinutes = 1200): User
    {
        $user = User::factory()->create();
        Subscription::factory()->create([
            'user_id' => $user->id,
            'remaining_minutes' => $remainingMinutes,
        ]);

        return $user;
    }

    public function test_check_in_requires_authentication(): void
    {
        $workspace = Workspace::factory()->create();

        $this->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token])
            ->assertStatus(401);
    }

    public function test_check_in_succeeds_with_valid_qr_and_subscription(): void
    {
        $user = $this->subscribedUser();
        $workspace = Workspace::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.workspace_id', $workspace->id)
            ->assertJsonPath('data.check_out_time', null)
            ->assertJsonStructure(['data' => ['attendance_id', 'workspace_name', 'check_in_time']]);

        $this->assertDatabaseHas('workspace_visits', [
            'user_id' => $user->id, 'workspace_id' => $workspace->id, 'status' => 'CHECKED_IN',
        ]);
    }

    public function test_check_in_rejects_invalid_qr(): void
    {
        $user = $this->subscribedUser();

        $this->actingAs($user)
            ->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => 'nope'])
            ->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_check_in_requires_active_subscription(): void
    {
        $user = User::factory()->create(); // no subscription
        $workspace = Workspace::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token])
            ->assertStatus(402)
            ->assertJsonPath('success', false);
    }

    public function test_duplicate_active_check_in_is_rejected(): void
    {
        $user = $this->subscribedUser();
        $workspace = Workspace::factory()->create();
        WorkspaceVisit::factory()->create(['user_id' => $user->id, 'workspace_id' => $workspace->id]);

        $this->actingAs($user)
            ->postJson('/api/v1/workspace-visits/check-in', ['qr_payload' => $workspace->qr_token])
            ->assertStatus(409)
            ->assertJsonPath('success', false);
    }

    public function test_check_out_calculates_minutes_deducts_and_writes_ledger(): void
    {
        $user = $this->subscribedUser(1200);
        $subscription = $user->subscriptions()->first();
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(90),
        ]);

        $this->actingAs($user)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")
            ->assertOk()
            ->assertJsonPath('data.study_minutes', 90);

        $this->assertSame(1110, (int) $subscription->refresh()->remaining_minutes);
        $this->assertDatabaseHas('subscription_ledgers', [
            'visit_id' => $visit->id, 'reason' => 'WORKSPACE_VISIT', 'balance_after' => 1110,
        ]);
    }

    public function test_check_out_is_idempotent(): void
    {
        $user = $this->subscribedUser(1200);
        $subscription = $user->subscriptions()->first();
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(90),
        ]);

        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")->assertOk();
        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")->assertOk();

        // Only one deduction despite two check-out calls.
        $this->assertSame(1110, (int) $subscription->refresh()->remaining_minutes);
        $this->assertDatabaseCount('subscription_ledgers', 1);
    }

    public function test_check_out_balance_never_negative(): void
    {
        $user = $this->subscribedUser(30);
        $subscription = $user->subscriptions()->first();
        $visit = WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'check_in_at' => now()->subMinutes(120),
        ]);

        $this->actingAs($user)->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")->assertOk();

        $this->assertSame(0, (int) $subscription->refresh()->remaining_minutes);
    }

    public function test_user_cannot_check_out_another_users_visit(): void
    {
        $owner = $this->subscribedUser();
        $other = $this->subscribedUser();
        $visit = WorkspaceVisit::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->postJson("/api/v1/workspace-visits/{$visit->id}/check-out")
            ->assertStatus(404);
    }

    public function test_active_visit_endpoint_returns_open_visit(): void
    {
        $user = $this->subscribedUser();
        WorkspaceVisit::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->getJson('/api/v1/workspace-visits/active')
            ->assertOk()
            ->assertJsonPath('data.check_out_time', null);
    }
}
