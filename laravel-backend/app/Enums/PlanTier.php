<?php

declare(strict_types=1);

namespace App\Enums;

enum PlanTier: string
{
    case FREE = 'FREE';
    case SILVER = 'SILVER';
    case GOLD = 'GOLD';
}
