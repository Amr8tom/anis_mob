<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class WorkspaceAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_owner_can_see_register_page(): void
    {
        $this->get('/workspace/register')
            ->assertOk()
            ->assertViewIs('workspace.auth.register');
    }

    public function test_workspace_owner_can_register_via_web(): void
    {
        $payload = [
            'full_name' => 'شريك تجريبي',
            'phone_number' => '01012345678',
            'whatsapp_number' => '01012345678',
            'password' => 'secret123',
            'workspace_name' => 'مساحة العمل التجريبية',
            'address' => 'القاهرة، مصر',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'day_calculation_hours' => 8,
        ];

        $response = $this->post('/workspace/register', $payload);

        // Assert database has user and workspace linked
        $this->assertDatabaseHas('users', [
            'phone_number' => '01012345678',
            'role' => UserRole::WORKSPACE_OWNER->value,
        ]);

        $user = User::where('phone_number', '01012345678')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('workspaces', [
            'owner_id' => $user->id,
            'name' => 'مساحة العمل التجريبية',
            'address' => 'القاهرة، مصر',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'day_calculation_hours' => 8,
        ]);

        // Assert session authentication is active
        $this->assertAuthenticatedAs($user);

        // Redirects to settings
        $response->assertRedirect(route('workspace.settings.edit'));
    }

    public function test_workspace_owner_can_see_login_page(): void
    {
        $this->get('/workspace/login')
            ->assertOk()
            ->assertViewIs('workspace.auth.login');
    }

    public function test_workspace_owner_can_login_via_web(): void
    {
        $user = User::factory()->create([
            'phone_number' => '01099999999',
            'password' => Hash::make('secret123'),
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
        ]);

        $response = $this->post('/workspace/login', [
            'phone_number' => '01099999999',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('workspace.settings.edit'));
    }

    public function test_regular_user_cannot_login_to_workspace_portal(): void
    {
        $user = User::factory()->create([
            'phone_number' => '01077777777',
            'password' => Hash::make('secret123'),
            'role' => UserRole::USER, // regular app user
        ]);

        $response = $this->post('/workspace/login', [
            'phone_number' => '01077777777',
            'password' => 'secret123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['phone_number']);
    }

    public function test_authenticated_owner_can_logout(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::WORKSPACE_OWNER,
        ]);

        $workspace = Workspace::factory()->create([
            'owner_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->post('/workspace/logout');

        $this->assertGuest();
        $response->assertRedirect(route('workspace.login'));
    }
}
