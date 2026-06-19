<?php

declare(strict_types=1);

namespace App\Domain\Room\Actions;

use App\Domain\Room\Contracts\RoomClientRepositoryInterface;
use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Domain\Room\Contracts\RoomReservationRepositoryInterface;
use App\Domain\Room\Data\CreateReservationData;
use App\Domain\Room\Data\ReservationResult;
use App\Enums\RoomReservationStatus;
use App\Exceptions\RoomSlotUnavailableException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Books a room for a client. Auto-registers the client by phone, computes cost
 * from the room price, and prevents double-booking via a per-room lock + overlap
 * check. Recurring series skip conflicting dates and report them.
 */
final readonly class CreateReservationAction
{
    /** Recurring series are expanded at most this far ahead. */
    private const HORIZON_DAYS = 84;

    public function __construct(
        private RoomRepositoryInterface $rooms,
        private RoomClientRepositoryInterface $clients,
        private RoomReservationRepositoryInterface $reservations,
    ) {}

    public function handle(string $workspaceId, string $ownerId, CreateReservationData $data): ReservationResult
    {
        return DB::transaction(function () use ($workspaceId, $ownerId, $data): ReservationResult {
            // Lock the room: serializes all bookings for this room so two requests
            // can't slip overlapping reservations past each other.
            $room = $this->rooms->lockActive($data->roomId, $workspaceId);
            if ($room === null) {
                throw new NotFoundHttpException('Room not found.');
            }

            $client = $this->clients->findOrCreate($workspaceId, $data->clientPhone, $data->clientName, $data->clientNote);

            $price = $room->hourly_price_cents;
            $cost = (int) round($data->durationMinutes / 60 * $price);

            $occurrences = $this->occurrences($data);
            $seriesId = count($occurrences) > 1 ? (string) Str::uuid() : null;

            $created = [];
            $skipped = [];

            foreach ($occurrences as $start) {
                $end = (clone $start)->addMinutes($data->durationMinutes);

                if ($this->reservations->hasOverlap($room->id, $start, $end)) {
                    $skipped[] = $start->format('Y-m-d H:i');

                    continue;
                }

                $created[] = $this->reservations->create([
                    'workspace_id' => $workspaceId,
                    'room_id' => $room->id,
                    'room_client_id' => $client->id,
                    'client_name' => $client->name,
                    'client_phone' => $client->phone,
                    'starts_at' => $start,
                    'ends_at' => $end,
                    'hourly_price_cents' => $price,
                    'total_cost_cents' => $cost,
                    'status' => RoomReservationStatus::RESERVED->value,
                    'note' => $data->note,
                    'series_id' => $seriesId,
                    'created_by_owner_id' => $ownerId,
                ]);
            }

            // A single booking (or a series with every date taken) that creates
            // nothing is a hard conflict.
            if ($created === []) {
                throw new RoomSlotUnavailableException;
            }

            Log::info('room.reserved', [
                'workspace_id' => $workspaceId,
                'room_id' => $room->id,
                'created' => count($created),
                'skipped' => count($skipped),
            ]);

            return new ReservationResult($created, $skipped);
        });
    }

    /**
     * @return array<int, Carbon>
     */
    private function occurrences(CreateReservationData $data): array
    {
        if (! $data->recurring || $data->weekdays === []) {
            return [$data->startsAt->copy()];
        }

        $weekdays = array_values(array_unique($data->weekdays));
        $until = $data->until ?? $data->startsAt->copy()->addDays(self::HORIZON_DAYS);
        if ($until->gt($data->startsAt->copy()->addDays(self::HORIZON_DAYS))) {
            $until = $data->startsAt->copy()->addDays(self::HORIZON_DAYS);
        }

        $hour = $data->startsAt->hour;
        $minute = $data->startsAt->minute;

        $occurrences = [];
        $cursor = $data->startsAt->copy()->startOfDay();
        $lastDay = $until->copy()->endOfDay();

        while ($cursor->lte($lastDay)) {
            if (in_array($cursor->dayOfWeek, $weekdays, true)) {
                $occurrences[] = $cursor->copy()->setTime($hour, $minute);
            }
            $cursor->addDay();
        }

        return $occurrences !== [] ? $occurrences : [$data->startsAt->copy()];
    }
}
