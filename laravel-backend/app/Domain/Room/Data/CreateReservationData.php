<?php

declare(strict_types=1);

namespace App\Domain\Room\Data;

use Illuminate\Support\Carbon;

final readonly class CreateReservationData
{
    /**
     * @param  array<int, int>  $weekdays  Carbon dayOfWeek values (0=Sun..6=Sat); used only when recurring.
     */
    public function __construct(
        public string $roomId,
        public string $clientName,
        public string $clientPhone,
        public ?string $clientNote,
        public Carbon $startsAt,
        public int $durationMinutes,
        public ?string $note,
        public bool $recurring,
        public array $weekdays,
        public ?Carbon $until,
    ) {}
}
