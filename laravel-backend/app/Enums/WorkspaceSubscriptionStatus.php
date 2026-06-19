<?php

declare(strict_types=1);

namespace App\Enums;

enum WorkspaceSubscriptionStatus: string
{
    case ACTIVE = 'ACTIVE';
    case EXHAUSTED = 'EXHAUSTED';
    case EXPIRED = 'EXPIRED';
    case CANCELLED = 'CANCELLED';
}
