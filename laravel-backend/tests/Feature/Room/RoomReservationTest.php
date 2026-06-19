<?php

declare(strict_types=1);

namespace Tests\Feature\Room;

use App\Domain\Room\Actions\CreateReservationAction;
use App\Domain\Room\Data\CreateReservationData;
use App\Enums\RoomReservationStatus;
use App\Exceptions\RoomSlotUnavailableException;
use App\Models\RoomReservation;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class RoomReservationTest extends TestCase
{
    use RefreshDatabase;

    private CreateReservationAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = resolve(CreateReservationAction::class);
    }

    /**
     * @return array{0: Workspace, 1: WorkspaceRoom, 2: User}
     */
    private function setup50PerHourRoom(): array
    {
        $workspace = Workspace::factory()->create();
        $owner = User::factory()->create();
        $room = WorkspaceRoom::factory()->create([
            'workspace_id' => $workspace->id,
            'hourly_price_cents' => 5000,   // 50 ج.م/hour
        ]);

        return [$workspace, $room, $owner];
    }

    private function data(string $roomId, Carbon $start, int $minutes, bool $recurring = false, array $weekdays = [], ?Carbon $until = null): CreateReservationData
    {
        return new CreateReservationData(
            roomId: $roomId,
            clientName: 'أحمد محمود',
            clientPhone: '01000000001',
            clientNote: null,
            startsAt: $start,
            durationMinutes: $minutes,
            note: null,
            recurring: $recurring,
            weekdays: $weekdays,
            until: $until,
        );
    }

    public function test_creates_reservation_auto_registers_client_and_computes_cost(): void
    {
        [$workspace, $room, $owner] = $this->setup50PerHourRoom();
        $start = now()->addDay()->setTime(16, 0);

        $result = $this->action->handle($workspace->id, $owner->id, $this->data($room->id, $start, 90));

        $this->assertSame(1, $result->createdCount());
        $this->assertSame(0, $result->skippedCount());

        // 90 min at 50/hour = 75.00 -> 7500 cents
        $this->assertDatabaseHas('room_reservations', [
            'room_id' => $room->id,
            'total_cost_cents' => 7500,
            'status' => 'RESERVED',
        ]);
        // Client auto-registered.
        $this->assertDatabaseHas('room_clients', [
            'workspace_id' => $workspace->id,
            'phone' => '01000000001',
        ]);
    }

    public function test_reuses_client_by_phone_on_second_booking(): void
    {
        [$workspace, $room, $owner] = $this->setup50PerHourRoom();

        $this->action->handle($workspace->id, $owner->id, $this->data($room->id, now()->addDay()->setTime(10, 0), 60));
        $this->action->handle($workspace->id, $owner->id, $this->data($room->id, now()->addDays(2)->setTime(10, 0), 60));

        $this->assertSame(1, \App\Models\RoomClient::where('workspace_id', $workspace->id)->count());
    }

    public function test_rejects_overlapping_single_booking(): void
    {
        [$workspace, $room, $owner] = $this->setup50PerHourRoom();
        $start = now()->addDay()->setTime(16, 0);
        $this->action->handle($workspace->id, $owner->id, $this->data($room->id, $start, 120));   // 16-18

        $this->expectException(RoomSlotUnavailableException::class);
        $this->action->handle($workspace->id, $owner->id, $this->data($room->id, $start->copy()->setTime(17, 0), 120));   // 17-19 overlaps
    }

    public function test_allows_back_to_back_bookings(): void
    {
        [$workspace, $room, $owner] = $this->setup50PerHourRoom();
        $day = now()->addDay();
        $this->action->handle($workspace->id, $owner->id, $this->data($room->id, $day->copy()->setTime(16, 0), 120));   // 16-18
        $result = $this->action->handle($workspace->id, $owner->id, $this->data($room->id, $day->copy()->setTime(18, 0), 120)); // 18-20

        $this->assertSame(1, $result->createdCount());
        $this->assertSame(2, RoomReservation::where('room_id', $room->id)->count());
    }

    public function test_recurring_skips_conflicting_occurrence(): void
    {
        [$workspace, $room, $owner] = $this->setup50PerHourRoom();

        // Start next Sunday 16:00; weekly on Sunday for 3 weeks.
        $firstSunday = now()->next(Carbon::SUNDAY)->setTime(16, 0);
        // Pre-book the SECOND Sunday to force a skip.
        RoomReservation::factory()->create([
            'workspace_id' => $workspace->id,
            'room_id' => $room->id,
            'starts_at' => $firstSunday->copy()->addWeek(),
            'ends_at' => $firstSunday->copy()->addWeek()->addHours(2),
            'status' => RoomReservationStatus::RESERVED,
        ]);

        $result = $this->action->handle(
            $workspace->id,
            $owner->id,
            $this->data($room->id, $firstSunday, 120, recurring: true, weekdays: [Carbon::SUNDAY], until: $firstSunday->copy()->addWeeks(2)),
        );

        // 3 Sundays in window, 1 already taken -> 2 created, 1 skipped.
        $this->assertSame(2, $result->createdCount());
        $this->assertSame(1, $result->skippedCount());
    }
}
