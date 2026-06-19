<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class WorkspaceLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => UserRole::ADMIN]);
    }

    private function pendingWorkspace(): Workspace
    {
        $owner = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
            'phone_number' => '01088887777',
            'password' => Hash::make('secret123'),
        ]);

        return Workspace::factory()->create([
            'owner_id' => $owner->id,
            'lifecycle_status' => 'PENDING',
            'is_active' => false,
        ]);
    }

    public function test_pending_owner_cannot_login(): void
    {
        $this->pendingWorkspace();

        $response = $this->post('/workspace/login', [
            'phone_number' => '01088887777',
            'password' => 'secret123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['phone_number']);
    }

    public function test_admin_approve_activates_workspace(): void
    {
        $workspace = $this->pendingWorkspace();

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.workspaces.approve', $workspace))
            ->assertRedirect(route('admin.workspaces.show', $workspace));

        $workspace->refresh();
        $this->assertSame('APPROVED', $workspace->lifecycle_status->value);
        $this->assertTrue($workspace->is_active);
        $this->assertNotNull($workspace->approved_at);
    }

    public function test_owner_of_approved_workspace_can_login(): void
    {
        $owner = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
            'phone_number' => '01088887777',
            'password' => Hash::make('secret123'),
        ]);
        Workspace::factory()->create(['owner_id' => $owner->id]); // APPROVED + active by default

        $this->post('/workspace/login', [
            'phone_number' => '01088887777',
            'password' => 'secret123',
        ])->assertRedirect(route('workspace.settings.edit'));
        $this->assertAuthenticatedAs($owner);
    }

    public function test_owner_of_suspended_workspace_cannot_login(): void
    {
        $owner = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
            'phone_number' => '01055554444',
            'password' => Hash::make('secret123'),
        ]);
        Workspace::factory()->create([
            'owner_id' => $owner->id,
            'lifecycle_status' => 'SUSPENDED',
            'is_active' => false,
        ]);

        $this->post('/workspace/login', [
            'phone_number' => '01055554444',
            'password' => 'secret123',
        ])->assertSessionHasErrors(['phone_number']);
        $this->assertGuest();
    }

    public function test_admin_reject_hard_deletes_workspace_and_owner_freeing_phone(): void
    {
        $workspace = $this->pendingWorkspace();
        $ownerId = $workspace->owner_id;

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.workspaces.reject', $workspace), ['reason' => 'بيانات غير مكتملة'])
            ->assertRedirect(route('admin.workspaces.index'));

        // Hard-deleted (not soft) so the unique phone number is freed.
        $this->assertDatabaseMissing('workspaces', ['id' => $workspace->id]);
        $this->assertDatabaseMissing('users', ['id' => $ownerId]);
    }

    public function test_admin_can_suspend_and_unsuspend(): void
    {
        $workspace = Workspace::factory()->create(); // APPROVED + active by default
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.workspaces.suspend', $workspace), ['reason' => 'مخالفة']);
        $workspace->refresh();
        $this->assertSame('SUSPENDED', $workspace->lifecycle_status->value);
        $this->assertFalse($workspace->is_active);
        $this->assertSame('مخالفة', $workspace->suspension_reason);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.workspaces.unsuspend', $workspace));
        $workspace->refresh();
        $this->assertSame('APPROVED', $workspace->lifecycle_status->value);
        $this->assertTrue($workspace->is_active);
        $this->assertNull($workspace->suspension_reason);
    }

    public function test_delete_is_blocked_while_a_visitor_is_checked_in(): void
    {
        $workspace = Workspace::factory()->create();
        WorkspaceVisit::factory()->create(['workspace_id' => $workspace->id]); // CHECKED_IN default

        $this->actingAs($this->admin(), 'admin')
            ->from(route('admin.workspaces.show', $workspace))
            ->delete(route('admin.workspaces.destroy', $workspace), ['reason' => 'إغلاق'])
            ->assertSessionHasErrors(['delete']);

        $this->assertNull($workspace->fresh()->deleted_at);
    }

    public function test_delete_soft_deletes_when_no_active_visits(): void
    {
        $workspace = Workspace::factory()->create();

        $this->actingAs($this->admin(), 'admin')
            ->delete(route('admin.workspaces.destroy', $workspace), ['reason' => 'إغلاق دائم'])
            ->assertRedirect(route('admin.workspaces.index'));

        $this->assertSoftDeleted('workspaces', ['id' => $workspace->id]);
    }

    public function test_admin_can_restore_a_deleted_workspace(): void
    {
        $workspace = Workspace::factory()->create();
        $workspace->delete();

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.workspaces.restore', $workspace->id))
            ->assertRedirect(route('admin.workspaces.show', $workspace->id));

        $restored = Workspace::find($workspace->id);
        $this->assertNotNull($restored);
        $this->assertFalse($restored->is_active);
    }
}
