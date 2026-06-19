<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminWorkspaceFinancialTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_workspace_view_report_and_mark_period_paid(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $owner = User::factory()->create(['phone_number' => '01099990000']);
        $this->actingAs($admin, 'admin')->post(route('admin.workspaces.store'), [
            'name' => 'Downtown Hub',
            'address' => 'Cairo',
            'capacity' => 50,
            'owner_phone' => $owner->phone_number,
        ])->assertRedirect();

        $workspace = Workspace::where('name', 'Downtown Hub')->firstOrFail();
        $visitTime = now()->subDays(2);
        WorkspaceVisit::factory()->checkedOut(120)->create([
            'workspace_id' => $workspace->id,
            'check_in_at' => $visitTime->copy()->subHours(2),
            'check_out_at' => $visitTime,
            'duration_minutes' => 120,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.workspaces.show', [
                'workspace' => $workspace,
                'from' => now()->startOfMonth()->format('Y-m-d'),
                'to' => now()->endOfMonth()->format('Y-m-d'),
            ]))
            ->assertOk()
            ->assertSee('2.00');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.workspaces.settlements.store', $workspace), [
                'payment_method' => 'CASH',
                'amount_cents' => 25000,
                'period_started_at' => $visitTime->copy()->startOfDay()->format('Y-m-d'),
                'period_ended_at' => $visitTime->copy()->endOfDay()->format('Y-m-d'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('workspace_settlements', [
            'workspace_id' => $workspace->id,
            'total_visits' => 1,
            'total_minutes' => 120,
            'free_minutes' => 120,
            'amount_cents' => 25000,
        ]);
        $this->assertDatabaseCount('admin_audit_logs', 2);
    }

    public function test_regular_user_cannot_create_or_settle_workspace(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::factory()->create();

        $this->actingAs($user)->post(route('admin.workspaces.store'), [
            'name' => 'Denied',
            'address' => 'Cairo',
            'owner_phone' => $user->phone_number,
        ])->assertRedirect(route('admin.login'));

        $this->actingAs($user)->post(route('admin.workspaces.settlements.store', $workspace), [
            'payment_method' => 'CASH',
            'amount_cents' => 1000,
            'period_started_at' => now()->startOfMonth()->format('Y-m-d'),
            'period_ended_at' => now()->endOfMonth()->format('Y-m-d'),
        ])->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_change_workspace_base_hourly_rate(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $workspace = Workspace::factory()->create([
            'payout_rate_cents_per_hour' => 1500,
            'hour_multiplier' => 1.5,
        ]);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.workspaces.billing.update', $workspace), [
                'base_hourly_rate_egp' => 20.50,
                'hour_multiplier' => 1.25,
                'day_calculation_hours' => 8,
            ])
            ->assertRedirect();

        $workspace->refresh();

        $this->assertSame(2050, $workspace->payout_rate_cents_per_hour);
        $this->assertSame(20.5, $workspace->baseHourlyRateEgp());
        $this->assertEquals(1.25, $workspace->hour_multiplier);
        $this->assertSame(25.63, $workspace->effectiveHourlyRateEgp());
        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $admin->id,
            'action' => 'UPDATE_WORKSPACE_BILLING',
            'entity_id' => $workspace->id,
        ]);
    }

    public function test_hour_multiplier_rejects_more_than_two_decimal_places(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $workspace = Workspace::factory()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.workspaces.billing.update', $workspace), [
                'base_hourly_rate_egp' => 15,
                'hour_multiplier' => 1.255,
                'day_calculation_hours' => 8,
            ])
            ->assertSessionHasErrors('hour_multiplier');
    }
}
