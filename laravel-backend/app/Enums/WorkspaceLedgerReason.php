<?php

declare(strict_types=1);

namespace App\Enums;

enum WorkspaceLedgerReason: string
{
    case ACTIVATION = 'ACTIVATION';
    case DIRECT_ASSIGNMENT = 'DIRECT_ASSIGNMENT';
    case WORKSPACE_VISIT = 'WORKSPACE_VISIT';
    case EXPIRY_FORFEIT = 'EXPIRY_FORFEIT';
    case CANCELLATION = 'CANCELLATION';
    case ADMIN_ADJUSTMENT = 'ADMIN_ADJUSTMENT';
}
