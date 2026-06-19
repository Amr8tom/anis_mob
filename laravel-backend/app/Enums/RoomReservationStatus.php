<?php

declare(strict_types=1);

namespace App\Enums;

enum RoomReservationStatus: string
{
    case RESERVED = 'RESERVED';
    case CANCELLED = 'CANCELLED';
}
