<?php

namespace Tests\Feature\Attendance;

use App\Domain\Attendance\Actions\CheckInAction;
use App\Enums\SubscriptionStatus;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceStatus;
use App\Exceptions\AlreadyCheckedInException;
use App\Exceptions\NoActiveSubscriptionException;
use App\Exceptions\OutOfHoursException;
use App\Exceptions\WorkspaceClosedException;
use App\Exceptions\WorkspaceFullException;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckInActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Workspace $workspace;

    private Subscription $subscription;

    private CheckInAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->workspace = Workspace::factory()->create([
            'status' => WorkspaceStatus::OPEN,
            'capacity' => 10,
            'manual_occupancy' => 0,
            'hour_multiplier' => 1.0,
            'qr_token' => 'VALID_QR',
            'is_active' => true,
        ]);

        $plan = Plan::factory()->create(['tier' => 'SILVER']);
        $this->subscription = Subscription::factory()->create([
            'user_id' => $this->user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::ACTIVE,
            'active_flag' => 1,
            'remaining_minutes' => 600,
        ]);

        $this->action = app(CheckInAction::class);
    }

    public function test_rejects_closed_workspace(): void
    {
        $this->workspace->update(['status' => WorkspaceStatus::CLOSED]);

        $this->expectException(WorkspaceClosedException::class);
        $this->action->handle('VALID_QR', $this->user->id);
    }

    public function test_rejects_full_workspace_status(): void
    {
        $this->workspace->update(['status' => WorkspaceStatus::FULL]);

        $this->expectException(WorkspaceFullException::class);
        $this->action->handle('VALID_QR', $this->user->id);
    }

    public function test_rejects_exceeded_capacity(): void
    {
        $this->workspace->update(['manual_occupancy' => 10]);

        $this->expectException(WorkspaceFullException::class);
        $this->action->handle('VALID_QR', $this->user->id);
    }

    public function test_manual_occupancy_overrides_active_visit_count(): void
    {
        $this->workspace->update(['capacity' => 2, 'manual_occupancy' => 1]);

        WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'status' => VisitStatus::CHECKED_IN,
            'active_flag' => 1,
        ]);

        $visit = $this->action->handle('VALID_QR', $this->user->id);

        $this->assertSame($this->user->id, $visit->user_id);
    }

    public function test_rejects_out_of_hours(): void
    {
        $this->workspace->update([
            'open_time' => now()->addHours(2)->format('H:i:s'),
            'close_time' => now()->addHours(10)->format('H:i:s'),
        ]);

        $this->expectException(OutOfHoursException::class);
        $this->action->handle('VALID_QR', $this->user->id);
    }

    public function test_allows_in_hours_including_overnight(): void
    {
        $this->workspace->update([
            'open_time' => now()->subHours(2)->format('H:i:s'),
            'close_time' => now()->addHours(10)->format('H:i:s'),
        ]);

        $visit = $this->action->handle('VALID_QR', $this->user->id);
        $this->assertNotNull($visit);
    }

    public function test_rejects_duplicate_active_visit(): void
    {
        WorkspaceVisit::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => VisitStatus::CHECKED_IN,
            'active_flag' => 1,
        ]);

        $this->expectException(AlreadyCheckedInException::class);
        $this->action->handle('VALID_QR', $this->user->id);
    }

    public function test_rejects_paid_subscription_with_less_than_fifteen_minutes(): void
    {
        $this->subscription->update(['remaining_minutes' => 14]);

        $this->expectException(NoActiveSubscriptionException::class);
        $this->action->handle('VALID_QR', $this->user->id);
    }

    public function test_allows_paid_subscription_with_exactly_fifteen_minutes(): void
    {
        $this->subscription->update(['remaining_minutes' => 15]);

        $visit = $this->action->handle('VALID_QR', $this->user->id);

        $this->assertSame($this->subscription->id, $visit->subscription_id);
    }
}
