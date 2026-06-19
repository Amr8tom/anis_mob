<?php

declare(strict_types=1);

namespace App\Domain\Room\Contracts;

use App\Models\RoomReservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface RoomReservationRepositoryInterface
{
    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): RoomReservation;

    /**
     * Does an active (RESERVED) reservation on this room overlap [start, end)?
     * Half-open: conflict iff existing.starts_at < end AND existing.ends_at > start.
     */
    public function hasOverlap(string $roomId, Carbon $start, Carbon $end): bool;

    public function findForWorkspace(string $reservationId, string $workspaceId): ?RoomReservation;

    /** @return Collection<int, RoomReservation> */
    public function upcomingForWorkspace(string $workspaceId, int $limit = 50): Collection;

    /** @return LengthAwarePaginator<int, RoomReservation> */
    public function paginateForClient(string $clientId, int $perPage): LengthAwarePaginator;

    /**
     * Active reservations for the workspace within [from, toExclusive) by starts_at.
     *
     * @return Collection<int, RoomReservation>
     */
    public function activeBetween(string $workspaceId, Carbon $from, Carbon $toExclusive): Collection;
}
