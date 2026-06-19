<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspaceSettlement;

use App\Domain\WorkspaceSettlement\Actions\CreateWorkspaceSettlementAction;
use App\Enums\BillingSource;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class CreateWorkspaceSettlementActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_settlement_snapshots_all_plan_tier_visit_totals_for_paid_period(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $workspace = Workspace::factory()->create();
        $periodStart = now()->subMonthNoOverflow()->startOfMonth();
        $periodEnd = now()->subMonthNoOverflow()->endOfMonth();

        $this->createVisit($workspace, BillingSource::FREE, 30, $periodStart->copy()->addDays(2));
        $this->createVisit($workspace, BillingSource::GLOBAL_SUBSCRIPTION, 60, $periodStart->copy()->addDays(3));
        $this->createVisit($workspace, BillingSource::WORKSPACE_SUBSCRIPTION, 90, $periodStart->copy()->addDays(4));

        $settlement = app(CreateWorkspaceSettlementAction::class)->handle(
            workspace: $workspace,
            adminId: $admin->id,
            paymentMethod: 'CASH',
            amountCents: 50000,
            periodStartedAt: $periodStart,
            periodEndedAt: $periodEnd,
        );

        $this->assertSame(3, $settlement->total_visits);
        $this->assertSame(180, $settlement->total_minutes);
        $this->assertSame(30, $settlement->free_minutes);
        $this->assertSame(60, $settlement->global_subscription_minutes);
        $this->assertSame(90, $settlement->workspace_subscription_minutes);
        $this->assertSame(50000, $settlement->amount_cents);
    }

    public function test_overlapping_paid_period_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $workspace = Workspace::factory()->create();
        $periodStart = now()->subMonthNoOverflow()->startOfMonth();
        $periodEnd = now()->subMonthNoOverflow()->endOfMonth();
        $this->createVisit($workspace, BillingSource::FREE, 60, $periodStart->copy()->addDay());
        $action = app(CreateWorkspaceSettlementAction::class);

        $action->handle($workspace, $admin->id, 'CASH', 1000, periodStartedAt: $periodStart, periodEndedAt: $periodEnd);

        $this->expectException(ValidationException::class);
        $action->handle($workspace, $admin->id, 'CASH', 1000, periodStartedAt: $periodStart, periodEndedAt: $periodEnd);
    }

    public function test_current_or_future_period_cannot_be_marked_paid(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $workspace = Workspace::factory()->create();

        $this->expectException(ValidationException::class);

        app(CreateWorkspaceSettlementAction::class)->handle(
            $workspace,
            $admin->id,
            'CASH',
            1000,
            periodStartedAt: now()->startOfDay(),
            periodEndedAt: now()->endOfDay(),
        );
    }

    private function createVisit(Workspace $workspace, BillingSource $source, int $minutes, \DateTimeInterface $checkedOutAt): void
    {
        $user = User::factory()->create();

        WorkspaceVisit::factory()->checkedOut($minutes)->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'billing_source' => $source,
            'check_in_at' => Carbon::instance($checkedOutAt)->subMinutes($minutes),
            'check_out_at' => $checkedOutAt,
            'duration_minutes' => $minutes,
            'billable_minutes' => $minutes,
        ]);
    }
}
