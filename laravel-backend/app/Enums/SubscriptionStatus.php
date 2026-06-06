<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionStatus: string
{
    case ACTIVE = 'ACTIVE';
    case EXPIRED = 'EXPIRED';
    case CANCELLED = 'CANCELLED';
}
