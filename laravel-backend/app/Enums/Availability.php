<?php

declare(strict_types=1);

namespace App\Enums;

enum Availability: string
{
    case ONLINE = 'ONLINE';
    case BUSY = 'BUSY';
    case OFFLINE = 'OFFLINE';
}
