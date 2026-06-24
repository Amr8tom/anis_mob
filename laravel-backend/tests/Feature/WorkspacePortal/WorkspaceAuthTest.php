<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceOwner;
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

        $this->assertDatabaseHas('workspace_owners', [
            'phone_number' => '01012345678',
        ]);
        $this->assertDatabaseMissing('users', [
            'phone_number' => '01012345678',
            'role' => UserRole::WORKSPACE_OWNER->value,
        ]);

        $owner = WorkspaceOwner::where('phone_number', '01012345678')->first();
        $this->assertNotNull($owner);

        // Held for admin review: pending and inactive (hidden from the mobile app).
        $this->assertDatabaseHas('workspaces', [
            'workspace_owner_id' => $owner->id,
            'name' => 'مساحة العمل التجريبية',
            'address' => 'القاهرة، مصر',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'day_calculation_hours' => 8,
            'lifecycle_status' => 'PENDING',
            'is_active' => false,
        ]);

        // The owner is NOT logged in while pending; they land on the pending page.
        $this->assertGuest('workspace_owner');
        $response->assertRedirect(route('workspace.pending'));
    }

    public function test_workspace_owner_can_see_login_page(): void
    {
        $this->get('/workspace/login')
            ->assertOk()
            ->assertViewIs('workspace.auth.login');
    }

    public function test_domain_root_redirects_to_workspace_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('workspace.login'));
    }

    public function test_unknown_web_routes_redirect_to_workspace_login(): void
    {
        $this->get('/unknown-web-route')
            ->assertRedirect(route('workspace.login'));
    }

    public function test_authenticated_workspace_owner_login_route_redirects_to_portal_not_public_home(): void
    {
        $owner = WorkspaceOwner::factory()->create();

        Workspace::factory()->create([
            'workspace_owner_id' => $owner->id,
        ]);

        $this->actingAs($owner, 'workspace_owner')
            ->get('/workspace/login')
            ->assertRedirect(route('workspace.settings.edit'));
    }

    public function test_workspace_login_page_can_switch_to_ltr_locale(): void
    {
        $this->get('/workspace/login?lang=en')
            ->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('dir="ltr"', false)
            ->assertSessionHas('locale', 'en');
    }

    public function test_workspace_owner_can_persist_locale_choice(): void
    {
        $owner = WorkspaceOwner::factory()->create([
            'locale' => null,
        ]);

        Workspace::factory()->create([
            'workspace_owner_id' => $owner->id,
        ]);

        $this->actingAs($owner, 'workspace_owner')
            ->from('/workspace/settings')
            ->post('/locale', ['locale' => 'tr'])
            ->assertRedirect('/workspace/settings')
            ->assertSessionHas('locale', 'tr');

        $this->assertDatabaseHas('workspace_owners', [
            'id' => $owner->id,
            'locale' => 'tr',
        ]);
    }

    public function test_workspace_owner_can_login_via_web(): void
    {
        $owner = WorkspaceOwner::factory()->create([
            'phone_number' => '01099999999',
            'password' => Hash::make('secret123'),
        ]);

        Workspace::factory()->create([
            'workspace_owner_id' => $owner->id,
        ]);

        $response = $this->post('/workspace/login', [
            'phone_number' => '01099999999',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($owner, 'workspace_owner');
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

        $this->assertGuest('workspace_owner');
        $response->assertSessionHasErrors(['phone_number']);
    }

    public function test_authenticated_owner_can_logout(): void
    {
        $owner = WorkspaceOwner::factory()->create();

        Workspace::factory()->create([
            'workspace_owner_id' => $owner->id,
        ]);

        $response = $this->actingAs($owner, 'workspace_owner')
            ->post('/workspace/logout');

        $this->assertGuest('workspace_owner');
        $response->assertRedirect(route('workspace.login'));
    }
}
