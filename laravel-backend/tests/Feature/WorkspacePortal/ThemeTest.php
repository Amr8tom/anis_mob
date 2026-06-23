<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Models\WorkspaceOwner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cookie;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_theme_is_dark(): void
    {
        $owner = WorkspaceOwner::factory()->create();

        $response = $this->actingAs($owner, 'workspace_owner')
            ->get(route('workspace.pending'));

        $response->assertOk();
        $response->assertViewHas('currentTheme', 'dark');
        $response->assertSee('data-theme="dark"', false);
    }

    public function test_theme_can_be_switched_to_light_and_persisted(): void
    {
        $owner = WorkspaceOwner::factory()->create(['theme' => 'dark']);

        $response = $this->actingAs($owner, 'workspace_owner')
            ->post(route('theme.switch'), [
                'theme' => 'light',
            ]);

        $response->assertRedirect();
        
        $this->assertEquals('light', $owner->fresh()->theme);
        
        $response->assertCookie('theme', 'light');
    }

    public function test_theme_switch_validates_input(): void
    {
        $owner = WorkspaceOwner::factory()->create();

        $response = $this->actingAs($owner, 'workspace_owner')
            ->post(route('theme.switch'), [
                'theme' => 'invalid-theme',
            ]);

        $response->assertSessionHasErrors('theme');
    }

    public function test_guest_can_switch_theme_via_cookie(): void
    {
        $response = $this->post(route('theme.switch'), [
            'theme' => 'light',
        ]);

        $response->assertRedirect();
        $response->assertCookie('theme', 'light');

        // Verify it gets applied from cookie.
        $this->withCookie('theme', 'light')
            ->get(route('workspace.login'))
            ->assertOk()
            ->assertViewHas('currentTheme', 'light');
    }

    public function test_query_parameter_overrides_cookie(): void
    {
        $owner = WorkspaceOwner::factory()->create(['theme' => 'light']);

        $this->actingAs($owner, 'workspace_owner')
            ->withCookie('theme', 'light')
            ->get(route('workspace.pending') . '?theme=dark')
            ->assertOk()
            ->assertViewHas('currentTheme', 'dark');
    }
}
