<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspaceSubscription;

use App\Domain\Attendance\Actions\OwnerCheckOutVisitAction;
use App\Domain\Attendance\Actions\OwnerRegisterVisitAction;
use App\Domain\WorkspaceSubscription\Actions\AssignWorkspaceSubscriptionByPhoneAction;
use App\Enums\BillingSource;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceWalkIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class WalkInWorkspaceSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private function paidWorkspace(int $dayHours = 2): Workspace
    {
        // multiplier 1.0 keeps the workspace-subscription rate at 1:1.
        return Workspace::factory()->create([
            'hour_multiplier' => 1.0,
            'day_calculation_hours' => $dayHours,
            'open_time' => null,
            'close_time' => null,
        ]);
    }

    public function test_assigning_plan_to_unregistered_phone_creates_walk_in_and_subscription(): void
    {
        $owner = User::factory()->create();
        $workspace = $this->paidWorkspace();
        $plan = WorkspacePlan::factory()->create([
            'workspace_id' => $workspace->id,
            'included_minutes' => 1200,
            'duration_days' => 30,
        ]);

        $sub = app(AssignWorkspaceSubscriptionByPhoneAction::class)
            ->handle($plan, '01000000001', (string) $owner->id, 'كريم');

        $walkIn = WorkspaceWalkIn::where('workspace_id', $workspace->id)
            ->where('phone_number', '01000000001')
            ->first();

        $this->assertNotNull($walkIn);
        $this->assertSame('كريم', $walkIn->full_name);
        $this->assertNull($sub->user_id);
        $this->assertSame($walkIn->id, $sub->walk_in_id);
        $this->assertSame(1200, $sub->remaining_minutes);

        $this->assertDatabaseHas('workspace_subscriptions', [
            'walk_in_id' => $walkIn->id,
            'user_id' => null,
            'workspace_id' => $workspace->id,
            'status' => 'ACTIVE',
            'remaining_minutes' => 1200,
        ]);
    }

    public function test_walk_in_check_in_is_funded_by_workspace_subscription_and_checkout_deducts_capped(): void
    {
        $owner = User::factory()->create();
        $workspace = $this->paidWorkspace(dayHours: 2); // cap = 120 minutes
        $plan = WorkspacePlan::factory()->create([
            'workspace_id' => $workspace->id,
            'included_minutes' => 1200,
            'duration_days' => 30,
        ]);

        app(AssignWorkspaceSubscriptionByPhoneAction::class)
            ->handle($plan, '01000000002', (string) $owner->id, 'سارة');

        // Check the walk-in in by phone.
        $visit = app(OwnerRegisterVisitAction::class)
            ->handle($workspace, '01000000002', null, (string) $owner->id);

        $this->assertSame(BillingSource::WORKSPACE_SUBSCRIPTION, $visit->billing_source);
        $this->assertNotNull($visit->workspace_subscription_id);

        // Simulate a 3-hour stay (exceeds the 2-hour daily cap). Use the query
        // builder to bypass the attendance-immutability model guard.
        \Illuminate\Support\Facades\DB::table('workspace_visits')
            ->where('id', $visit->id)
            ->update(['check_in_at' => now()->subHours(3)]);

        $closed = app(OwnerCheckOutVisitAction::class)->handle($workspace, $visit->id);

        // Billed only the daily cap (120 min), not the full 180.
        $this->assertSame(120, $closed->deducted_minutes);
        $this->assertDatabaseHas('workspace_subscriptions', [
            'id' => $visit->workspace_subscription_id,
            'remaining_minutes' => 1080, // 1200 - 120
        ]);
        $this->assertDatabaseHas('workspace_subscription_ledgers', [
            'workspace_subscription_id' => $visit->workspace_subscription_id,
            'change_minutes' => -120,
        ]);
    }

    public function test_walk_in_without_plan_checks_in_free(): void
    {
        $owner = User::factory()->create();
        $workspace = $this->paidWorkspace();

        $visit = app(OwnerRegisterVisitAction::class)
            ->handle($workspace, '01000000003', 'بدون باقة', (string) $owner->id);

        $this->assertSame(BillingSource::FREE, $visit->billing_source);
        $this->assertNull($visit->workspace_subscription_id);
    }
}
