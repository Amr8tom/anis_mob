<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Models\Workspace;
use App\Models\WorkspaceOwner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_renders_arabic_rtl_by_default_and_per_lang(): void
    {
        $this->get('/workspace/login?lang=ar')
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('بوابة الشركاء');
    }

    public function test_login_renders_english_ltr(): void
    {
        $this->get('/workspace/login?lang=en')
            ->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('dir="ltr"', false)
            ->assertSee('Partner Portal');
    }

    public function test_login_renders_turkish_ltr(): void
    {
        $this->get('/workspace/login?lang=tr')
            ->assertOk()
            ->assertSee('lang="tr"', false)
            ->assertSee('dir="ltr"', false)
            ->assertSee('İş Ortağı Portalı');
    }

    public function test_authenticated_owner_can_persist_locale(): void
    {
        $owner = WorkspaceOwner::factory()->create(['locale' => null]);

        Workspace::factory()->create([
            'workspace_owner_id' => $owner->id,
        ]);

        $this->actingAs($owner, 'workspace_owner')
            ->post(route('locale.switch'), ['locale' => 'en'])
            ->assertRedirect();

        $this->assertSame('en', $owner->refresh()->locale);
    }

    public function test_guest_is_redirected_to_workspace_login_from_protected_routes(): void
    {
        $this->get('/workspace/visits')
            ->assertRedirect(route('workspace.login'))
            ->assertSessionHas('error');
    }

    public function test_unsupported_locale_falls_back_to_default(): void
    {
        $this->get('/workspace/login?lang=zz')
            ->assertOk()
            ->assertSee('dir="rtl"', false);   // falls back to ar (default)
    }
}
