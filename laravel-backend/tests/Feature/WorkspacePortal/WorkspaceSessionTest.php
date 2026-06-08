<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\StudySession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WorkspaceSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_sessions(): void
    {
        $this->get(route('workspace.sessions.index'))
            ->assertRedirect(route('workspace.login'));
    }

    public function test_owner_can_view_sessions_list(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
        ]);

        $session = StudySession::factory()->create([
            'workspace_id' => $workspace->id,
            'host_id' => $user->id,
            'title' => 'My Special Workspace Session',
        ]);

        $this->actingAs($user)
            ->get(route('workspace.sessions.index'))
            ->assertOk()
            ->assertViewIs('workspace.sessions.index')
            ->assertSee('My Special Workspace Session');
    }

    public function test_owner_can_create_session(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
        ]);

        $payload = [
            'title' => 'Calculus Live Prep',
            'description' => 'A rigorous session reviewing limits and integrals.',
            'instructor_name' => 'Dr. John Doe',
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'max_participants' => 15,
            'price_cents' => 2500, // 25 EGP
        ];

        $response = $this->actingAs($user)
            ->post(route('workspace.sessions.store'), $payload);

        $response->assertRedirect(route('workspace.sessions.index'));

        $this->assertDatabaseHas('study_sessions', [
            'workspace_id' => $workspace->id,
            'host_id' => $user->id,
            'title' => 'Calculus Live Prep',
            'instructor_name' => 'Dr. John Doe',
            'price_cents' => 2500,
            'max_seats' => 15,
        ]);
    }

    public function test_owner_cannot_modify_session_of_other_workspace(): void
    {
        $owner1 = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace1 = Workspace::factory()->create(['owner_id' => $owner1->id]);

        $owner2 = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace2 = Workspace::factory()->create(['owner_id' => $owner2->id]);

        $sessionOfWorkspace2 = StudySession::factory()->create([
            'workspace_id' => $workspace2->id,
            'host_id' => $owner2->id,
            'title' => 'Workspace 2 Session',
        ]);

        // Owner 1 tries to access/edit/update/delete Owner 2's session
        $this->actingAs($owner1)
            ->get(route('workspace.sessions.edit', $sessionOfWorkspace2))
            ->assertStatus(403);

        $this->actingAs($owner1)
            ->put(route('workspace.sessions.update', $sessionOfWorkspace2), [
                'title' => 'Hacked Title',
                'description' => 'Desc',
                'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
                'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
                'price_cents' => 0,
            ])
            ->assertStatus(403);

        $this->actingAs($owner1)
            ->delete(route('workspace.sessions.destroy', $sessionOfWorkspace2))
            ->assertStatus(403);
    }

    public function test_owner_can_update_session(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
        ]);

        $session = StudySession::factory()->create([
            'workspace_id' => $workspace->id,
            'host_id' => $user->id,
            'title' => 'Old Title',
            'instructor_name' => 'Old Instructor',
            'price_cents' => 1000,
        ]);

        $payload = [
            'title' => 'New Title',
            'description' => 'New Description',
            'instructor_name' => 'New Instructor',
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'max_participants' => 8,
            'price_cents' => 1500,
        ];

        $this->actingAs($user)
            ->put(route('workspace.sessions.update', $session), $payload)
            ->assertRedirect(route('workspace.sessions.index'));

        $session->refresh();

        $this->assertEquals('New Title', $session->title);
        $this->assertEquals('New Instructor', $session->instructor_name);
        $this->assertEquals(1500, $session->price_cents);
        $this->assertEquals(8, $session->max_seats);
    }

    public function test_owner_can_delete_session(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
        ]);

        $session = StudySession::factory()->create([
            'workspace_id' => $workspace->id,
            'host_id' => $user->id,
            'title' => 'Delete Me',
        ]);

        $this->actingAs($user)
            ->delete(route('workspace.sessions.destroy', $session))
            ->assertRedirect(route('workspace.sessions.index'));

        $this->assertDatabaseMissing('study_sessions', [
            'id' => $session->id,
        ]);
    }
}
