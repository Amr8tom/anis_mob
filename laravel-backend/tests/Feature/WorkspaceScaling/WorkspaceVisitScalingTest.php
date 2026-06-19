<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspaceScaling;

use App\Domain\WorkspaceClient\Actions\UpsertWorkspaceClientFromVisitAction;
use App\Domain\WorkspaceSettlement\Actions\RefreshWorkspaceDailyVisitStatsAction;
use App\Enums\BillingSource;
use App\Enums\UserRole;
use App\Enums\VisitStatus;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceClient;
use App\Models\WorkspaceDailyVisitStat;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class WorkspaceVisitScalingTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_client_summary_is_idempotent_for_registered_user(): void
    {
        $workspace = Workspace::factory()->create();
        $user = User::factory()->create(['full_name' => 'Fast Search User', 'phone_number' => '01012345678']);
        $visit = WorkspaceVisit::factory()->checkedOut(75)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
            'check_in_at' => now()->subMinutes(75),
            'check_out_at' => now(),
        ]);
        $action = app(UpsertWorkspaceClientFromVisitAction::class);

        $this->assertTrue($action->handle($visit->id));
        $this->assertFalse($action->handle($visit->id));

        $this->assertDatabaseHas('workspace_clients', [
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'client_type' => 'USER',
            'full_name_snapshot' => 'Fast Search User',
            'phone_number_normalized' => '01012345678',
            'total_visits' => 1,
            'total_minutes' => 75,
            'global_subscription_visits' => 1,
        ]);
        $this->assertNotNull($visit->fresh()->workspace_client_counted_at);
    }

    public function test_backfill_command_populates_walk_in_client_summaries(): void
    {
        $workspace = Workspace::factory()->create();
        $walkIn = WorkspaceWalkIn::create([
            'workspace_id' => $workspace->id,
            'full_name' => 'Walk In Visitor',
            'phone_number' => '+20 10 9999 8888',
        ]);
        WorkspaceVisit::factory()->checkedOut(45)->create([
            'workspace_id' => $workspace->id,
            'user_id' => null,
            'walk_in_id' => $walkIn->id,
            'billing_source' => BillingSource::FREE,
            'check_in_at' => now()->subMinutes(45),
            'check_out_at' => now(),
        ]);

        $this->artisan('workspace-clients:backfill')
            ->expectsOutput('Scanned 1 visits; counted 1 workspace clients.')
            ->assertSuccessful();

        $this->assertDatabaseHas('workspace_clients', [
            'workspace_id' => $workspace->id,
            'walk_in_id' => $walkIn->id,
            'client_type' => 'WALK_IN',
            'full_name_snapshot' => 'Walk In Visitor',
            'phone_number_normalized' => '201099998888',
            'total_visits' => 1,
            'free_visits' => 1,
        ]);
    }

    public function test_daily_rollup_stores_visitor_counts(): void
    {
        $workspace = Workspace::factory()->create();
        $date = Carbon::parse('2026-01-10 10:00:00');
        $registered = User::factory()->create();
        $walkIn = WorkspaceWalkIn::create([
            'workspace_id' => $workspace->id,
            'full_name' => 'Rollup Walkin',
            'phone_number' => '01033333333',
        ]);

        WorkspaceVisit::factory()->checkedOut(30)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $registered->id,
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION,
            'check_in_at' => $date->copy()->subMinutes(30),
            'check_out_at' => $date,
        ]);
        WorkspaceVisit::factory()->checkedOut(20)->create([
            'workspace_id' => $workspace->id,
            'user_id' => null,
            'walk_in_id' => $walkIn->id,
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION,
            'check_in_at' => $date->copy()->addHour()->subMinutes(20),
            'check_out_at' => $date->copy()->addHour(),
        ]);

        app(RefreshWorkspaceDailyVisitStatsAction::class)->handle($workspace->id, $date);

        $this->assertDatabaseHas('workspace_daily_visit_stats', [
            'workspace_id' => $workspace->id,
            'stat_date' => '2026-01-10 00:00:00',
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION->value,
            'visits_count' => 2,
            'visitors_count' => 2,
            'unique_visitors_count' => 2,
            'registered_visitors_count' => 1,
            'walk_in_visitors_count' => 1,
            'total_minutes' => 50,
        ]);
    }

    public function test_retention_archives_and_deletes_only_old_counted_visits_with_rollups(): void
    {
        $workspace = Workspace::factory()->create();
        $user = User::factory()->create();
        $oldCheckout = now()->subDays(400);
        $recentCheckout = now()->subDays(30);
        $oldVisit = WorkspaceVisit::factory()->checkedOut(60)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'billing_source' => BillingSource::FREE,
            'check_in_at' => $oldCheckout->copy()->subHour(),
            'check_out_at' => $oldCheckout,
            'workspace_client_counted_at' => now(),
        ]);
        $recentVisit = WorkspaceVisit::factory()->checkedOut(60)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'billing_source' => BillingSource::FREE,
            'check_in_at' => $recentCheckout->copy()->subHour(),
            'check_out_at' => $recentCheckout,
            'workspace_client_counted_at' => now(),
        ]);
        $activeVisit = WorkspaceVisit::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => User::factory()->create()->id,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subDays(500),
        ]);
        WorkspaceDailyVisitStat::create([
            'workspace_id' => $workspace->id,
            'stat_date' => $oldCheckout->toDateString(),
            'billing_source' => BillingSource::FREE,
            'visits_count' => 1,
            'visitors_count' => 1,
            'unique_visitors_count' => 1,
            'registered_visitors_count' => 1,
            'walk_in_visitors_count' => 0,
            'total_minutes' => 60,
        ]);

        $this->artisan('workspace-visits:retention', ['--older-than-days' => 365])
            ->expectsOutput('Scanned 1; archived 1; deleted 1; skipped 0.')
            ->assertSuccessful();

        $this->assertDatabaseMissing('workspace_visits', ['id' => $oldVisit->id]);
        $this->assertDatabaseHas('workspace_visit_archives', ['id' => $oldVisit->id, 'workspace_id' => $workspace->id]);
        $this->assertDatabaseHas('workspace_visits', ['id' => $recentVisit->id]);
        $this->assertDatabaseHas('workspace_visits', ['id' => $activeVisit->id]);
    }

    public function test_scaling_health_passes_when_summaries_and_rollups_are_clean(): void
    {
        $this->artisan('workspace-scaling:health', ['--retention-threshold' => 0])
            ->expectsOutputToContain('Workspace scaling health check passed.')
            ->assertSuccessful();
    }

    public function test_scaling_health_fails_when_checked_out_visit_is_not_counted(): void
    {
        $yesterday = now()->subDay();
        WorkspaceVisit::factory()->checkedOut(30)->create([
            'check_in_at' => $yesterday->copy()->subMinutes(30),
            'check_out_at' => $yesterday,
        ]);

        $this->artisan('workspace-scaling:health')
            ->expectsOutputToContain('Workspace scaling health check failed.')
            ->assertExitCode(Command::FAILURE);
    }

    public function test_rollup_backfill_populates_missing_daily_stats(): void
    {
        $workspace = Workspace::factory()->create();
        $date = today()->subDays(3);
        WorkspaceVisit::factory()->checkedOut(80)->create([
            'workspace_id' => $workspace->id,
            'check_in_at' => $date->copy()->addHours(10),
            'check_out_at' => $date->copy()->addHours(11),
            'billing_source' => BillingSource::FREE,
        ]);

        $this->artisan('reports:backfill-rollups', [
            '--from' => $date->toDateString(),
            '--to' => $date->toDateString(),
        ])
            ->expectsOutput('Rollup backfill processed 1 workspace/day pairs.')
            ->assertSuccessful();

        $this->assertDatabaseHas('workspace_daily_visit_stats', [
            'workspace_id' => $workspace->id,
            'billing_source' => BillingSource::FREE->value,
            'visits_count' => 1,
            'total_minutes' => 80,
        ]);
    }

    public function test_retention_dry_run_does_not_write(): void
    {
        [$workspace, $visit] = $this->oldCountedVisitWithRollup();

        $this->artisan('workspace-visits:retention', ['--older-than-days' => 365, '--dry-run' => true])
            ->expectsOutput('Scanned 1; archived 1; deleted 1; skipped 0.')
            ->expectsOutput('skipped_missing_rollup=0')
            ->expectsOutput('skipped_accounting_reference=0')
            ->expectsOutput('skipped_not_counted=0')
            ->assertSuccessful();

        $this->assertDatabaseHas('workspace_visits', ['id' => $visit->id, 'workspace_id' => $workspace->id]);
        $this->assertDatabaseMissing('workspace_visit_archives', ['id' => $visit->id]);
    }

    public function test_retention_archive_only_keeps_raw_visit(): void
    {
        [, $visit] = $this->oldCountedVisitWithRollup();

        $this->artisan('workspace-visits:retention', ['--older-than-days' => 365, '--archive-only' => true])
            ->expectsOutput('Scanned 1; archived 1; deleted 0; skipped 0.')
            ->assertSuccessful();

        $this->assertDatabaseHas('workspace_visits', ['id' => $visit->id]);
        $this->assertDatabaseHas('workspace_visit_archives', ['id' => $visit->id]);
    }

    public function test_retention_respects_max_delete(): void
    {
        $first = $this->oldCountedVisitWithRollup()[1];
        $second = $this->oldCountedVisitWithRollup()[1];

        $this->artisan('workspace-visits:retention', ['--older-than-days' => 365, '--max-delete' => 1])
            ->expectsOutput('Scanned 1; archived 1; deleted 1; skipped 0.')
            ->assertSuccessful();

        $remaining = WorkspaceVisit::query()
            ->whereIn('id', [$first->id, $second->id])
            ->count();
        $this->assertSame(1, $remaining);
    }

    public function test_workspace_clients_repair_recalculates_drifted_counters(): void
    {
        $workspace = Workspace::factory()->create();
        $user = User::factory()->create(['full_name' => 'Repair User', 'phone_number' => '01077777777']);
        WorkspaceVisit::factory()->checkedOut(40)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'billing_source' => BillingSource::WORKSPACE_SUBSCRIPTION,
            'check_in_at' => now()->subMinutes(40),
            'check_out_at' => now(),
        ]);
        WorkspaceClient::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'total_visits' => 99,
            'total_minutes' => 999,
        ]);

        $this->artisan('workspace-clients:repair', ['--workspace' => $workspace->id])
            ->expectsOutput('Workspace clients repaired 1 rows.')
            ->assertSuccessful();

        $this->assertDatabaseHas('workspace_clients', [
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'total_visits' => 1,
            'total_minutes' => 40,
            'workspace_subscription_visits' => 1,
        ]);
        $this->assertSame(0, WorkspaceVisit::query()->whereNull('workspace_client_counted_at')->count());
    }

    public function test_scaling_indexes_exist(): void
    {
        $this->assertIndexes('workspace_visits', [
            'visits_ws_status_out_source_idx',
            'visits_ws_status_in_idx',
            'visits_ws_user_in_idx',
            'visits_ws_walkin_in_idx',
            'visits_ws_checkout_queue_idx',
            'visits_ws_client_counted_idx',
        ]);
        $this->assertIndexes('workspace_clients', [
            'workspace_clients_last_visit_idx',
            'workspace_clients_phone_idx',
            'workspace_clients_name_idx',
            'workspace_clients_type_idx',
            'workspace_clients_source_idx',
        ]);
        $this->assertIndexes('workspace_subscriptions', [
            'ws_subs_workspace_created_idx',
            'ws_subs_workspace_status_created_idx',
            'ws_subs_expiry_idx',
        ]);
        $this->assertIndexes('workspace_subscription_codes', [
            'ws_codes_workspace_status_created_idx',
            'ws_codes_status_expires_idx',
        ]);
    }

    public function test_client_index_can_render_from_summary_rows_without_raw_visits(): void
    {
        $owner = User::factory()->create(['role' => UserRole::WORKSPACE_OWNER]);
        $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
        WorkspaceClient::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => User::factory()->create()->id,
            'full_name_snapshot' => 'Summary Only',
            'phone_number_snapshot' => '01055555555',
            'phone_number_normalized' => '01055555555',
            'total_visits' => 3,
            'total_minutes' => 180,
            'global_subscription_visits' => 3,
            'last_billing_source' => BillingSource::GLOBAL_SUBSCRIPTION,
        ]);

        $this->assertSame(0, WorkspaceVisit::query()->count());

        $this->actingAs($owner)
            ->get(route('workspace.clients.index', ['search' => '010555']))
            ->assertOk()
            ->assertSee('Summary Only')
            ->assertSee('180 دقيقة');
    }

    /**
     * @return array{Workspace, WorkspaceVisit}
     */
    private function oldCountedVisitWithRollup(): array
    {
        $workspace = Workspace::factory()->create();
        $oldCheckout = now()->subDays(400);
        $visit = WorkspaceVisit::factory()->checkedOut(60)->create([
            'workspace_id' => $workspace->id,
            'billing_source' => BillingSource::FREE,
            'check_in_at' => $oldCheckout->copy()->subHour(),
            'check_out_at' => $oldCheckout,
            'workspace_client_counted_at' => now(),
        ]);
        WorkspaceDailyVisitStat::create([
            'workspace_id' => $workspace->id,
            'stat_date' => $oldCheckout->toDateString(),
            'billing_source' => BillingSource::FREE,
            'visits_count' => 1,
            'visitors_count' => 1,
            'unique_visitors_count' => 1,
            'registered_visitors_count' => 1,
            'walk_in_visitors_count' => 0,
            'total_minutes' => 60,
        ]);

        return [$workspace, $visit];
    }

    /**
     * @param  list<string>  $expected
     */
    private function assertIndexes(string $table, array $expected): void
    {
        $actual = collect(DB::select("PRAGMA index_list('{$table}')"))
            ->pluck('name')
            ->all();

        foreach ($expected as $index) {
            $this->assertContains($index, $actual, "Missing index {$index} on {$table}.");
        }
    }
}
