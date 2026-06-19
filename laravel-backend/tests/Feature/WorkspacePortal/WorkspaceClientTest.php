<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Domain\WorkspaceClient\Actions\UpsertWorkspaceClientFromVisitAction;
use App\Enums\BillingSource;
use App\Enums\PlanTier;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WorkspaceClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_filter_clients_and_see_result_totals_and_revenue(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id, 'hour_multiplier' => 2]);
        $silverUser = User::factory()->create(['full_name' => 'Silver Visitor', 'phone_number' => '01011111111']);
        $freeUser = User::factory()->create(['full_name' => 'Free Visitor', 'phone_number' => '01022222222']);

        $silverVisit = WorkspaceVisit::factory()->checkedOut(120)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $silverUser->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'plan_tier_snapshot' => PlanTier::SILVER,
            'check_in_at' => now()->subHour(),
        ]);
        $freeVisit = WorkspaceVisit::factory()->checkedOut(60)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $freeUser->id,
            'plan_tier_snapshot' => PlanTier::FREE,
            'check_in_at' => now()->subHour(),
        ]);
        app(UpsertWorkspaceClientFromVisitAction::class)->handle($silverVisit->id);
        app(UpsertWorkspaceClientFromVisitAction::class)->handle($freeVisit->id);

        $this->actingAs($owner)
            ->get(route('workspace.clients.index', ['plan' => BillingSource::GLOBAL_SUBSCRIPTION->value]))
            ->assertOk()
            ->assertSee('Silver Visitor')
            ->assertSee('عالمي')
            ->assertSee('مجموع الدقائق')
            ->assertSee('120 دقيقة')
            ->assertDontSee('Free Visitor')
            ->assertViewHas('summary', fn (array $summary): bool => $summary === [
                'total_visitors' => 1,
                'total_visits' => 1,
                'total_minutes' => 120,
                'total_revenue' => 60.0,
            ]);
    }

    public function test_end_datetime_filter_includes_the_entire_selected_minute(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        $includedUser = User::factory()->create(['full_name' => 'Included Visitor']);
        $excludedUser = User::factory()->create(['full_name' => 'Excluded Visitor']);

        $includedVisit = WorkspaceVisit::factory()->checkedOut(60)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $includedUser->id,
            'check_in_at' => '2026-06-15 13:02:59',
            'check_out_at' => '2026-06-15 13:02:59',
        ]);
        $excludedVisit = WorkspaceVisit::factory()->checkedOut(60)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $excludedUser->id,
            'check_in_at' => '2026-06-15 13:03:00',
            'check_out_at' => '2026-06-15 13:03:00',
        ]);
        app(UpsertWorkspaceClientFromVisitAction::class)->handle($includedVisit->id);
        app(UpsertWorkspaceClientFromVisitAction::class)->handle($excludedVisit->id);

        $this->actingAs($owner)
            ->get(route('workspace.clients.index', ['to' => '2026-06-15T13:02']))
            ->assertOk()
            ->assertSee('Included Visitor')
            ->assertDontSee('Excluded Visitor');
    }

    public function test_client_detail_requires_an_explicit_client_type(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        Workspace::factory()->create(['owner_id' => $owner->id]);
        $client = User::factory()->create();

        $this->actingAs($owner)
            ->get(route('workspace.clients.show', $client))
            ->assertSessionHasErrors('type');
    }

    public function test_clients_index_uses_summary_table_instead_of_grouping_raw_visits(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Web/WorkspaceClientController.php'));

        $this->assertStringContainsString('WorkspaceClient::query()', $controller);
        $this->assertStringContainsString('phone_number_normalized', $controller);
        $this->assertStringNotContainsString('unionAll', $controller);
        $this->assertStringNotContainsString('GROUP_CONCAT', $controller);
    }
}
