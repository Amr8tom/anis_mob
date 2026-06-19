<?php

declare(strict_types=1);

namespace App\Domain\Room\Repositories;

use App\Domain\Room\Contracts\RoomReservationRepositoryInterface;
use App\Enums\RoomReservationStatus;
use App\Models\RoomReservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class EloquentRoomReservationRepository implements RoomReservationRepositoryInterface
{
    public function create(array $attributes): RoomReservation
    {
        return RoomReservation::create($attributes);
    }

    public function hasOverlap(string $roomId, Carbon $start, Carbon $end): bool
    {
        return RoomReservation::query()
            ->where('room_id', $roomId)
            ->where('status', RoomReservationStatus::RESERVED->value)
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->exists();
    }

    public function findForWorkspace(string $reservationId, string $workspaceId): ?RoomReservation
    {
        return RoomReservation::query()
            ->where('id', $reservationId)
            ->where('workspace_id', $workspaceId)
            ->first();
    }

    public function upcomingForWorkspace(string $workspaceId, int $limit = 50): Collection
    {
        return RoomReservation::query()
            ->with(['room', 'client'])
            ->where('workspace_id', $workspaceId)
            ->where('status', RoomReservationStatus::RESERVED->value)
            ->where('ends_at', '>=', now())
            ->orderBy('starts_at')
            ->limit($limit)
            ->get();
    }

    public function paginateForClient(string $clientId, int $perPage): LengthAwarePaginator
    {
        return RoomReservation::query()
            ->with('room')
            ->where('room_client_id', $clientId)
            ->latest('starts_at')
            ->paginate($perPage);
    }

    public function activeBetween(string $workspaceId, Carbon $from, Carbon $toExclusive): Collection
    {
        return RoomReservation::query()
            ->with(['room', 'client'])
            ->where('workspace_id', $workspaceId)
            ->where('status', RoomReservationStatus::RESERVED->value)
            ->where('starts_at', '>=', $from)
            ->where('starts_at', '<', $toExclusive)
            ->orderByDesc('starts_at')
            ->get();
    }
}
