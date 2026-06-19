<?php

declare(strict_types=1);

namespace Tests\Unit\Attendance;

use App\Domain\Attendance\Actions\RequestCheckoutAction;
use App\Enums\PlanTier;
use App\Enums\VisitStatus;
use App\Exceptions\VisitAlreadyClosedException;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RequestCheckoutActionTest extends TestCase
{
    use RefreshDatabase;

    private RequestCheckoutAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = resolve(RequestCheckoutAction::class);
    }

    private function openVisit(): WorkspaceVisit
    {
        $user = User::factory()->create();
        $workspace = Workspace::factory()->create(['checkout_mode' => 'APPROVAL']);

        return WorkspaceVisit::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'plan_tier_snapshot' => PlanTier::SILVER,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now()->subMinutes(30),
        ]);
    }

    public function test_marks_request_and_keeps_visit_open(): void
    {
        $visit = $this->openVisit();

        $result = $this->action->handle($visit->id, (string) $visit->user_id);

        $this->assertSame(VisitStatus::CHECKED_IN, $result->status);
        $this->assertNotNull($result->checkout_requested_at);
    }

    public function test_is_idempotent(): void
    {
        $visit = $this->openVisit();

        $first = $this->action->handle($visit->id, (string) $visit->user_id);
        $second = $this->action->handle($visit->id, (string) $visit->user_id);

        $this->assertEquals(
            $first->checkout_requested_at->toIso8601String(),
            $second->checkout_requested_at->toIso8601String(),
        );
    }

    public function test_rejects_closed_visit(): void
    {
        $visit = $this->openVisit();
        $visit->update([
            'status' => VisitStatus::CHECKED_OUT,
            'check_out_at' => now(),
            'duration_minutes' => 30,
            'billable_minutes' => 30,
            'deducted_minutes' => 30,
            'hour_multiplier_applied' => 1.0,
            'active_flag' => null,
        ]);

        $this->expectException(VisitAlreadyClosedException::class);
        $this->action->handle($visit->id, (string) $visit->user_id);
    }
}
