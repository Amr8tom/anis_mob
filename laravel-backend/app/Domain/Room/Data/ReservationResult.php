<?php

declare(strict_types=1);

namespace App\Domain\Room\Data;

use App\Models\RoomReservation;

final readonly class ReservationResult
{
    /**
     * @param  array<int, RoomReservation>  $created
     * @param  array<int, string>  $skipped  Human-readable datetimes that conflicted.
     */
    public function __construct(
        public array $created,
        public array $skipped,
    ) {}

    public function createdCount(): int
    {
        return count($this->created);
    }

    public function skippedCount(): int
    {
        return count($this->skipped);
    }
}
