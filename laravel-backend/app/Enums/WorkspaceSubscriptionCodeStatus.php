<?php

declare(strict_types=1);

namespace App\Enums;

enum WorkspaceSubscriptionCodeStatus: string
{
    case UNUSED = 'UNUSED';
    case REDEEMED = 'REDEEMED';
    case REVOKED = 'REVOKED';
    case EXPIRED = 'EXPIRED';
}
