<?php

declare(strict_types=1);

namespace App\Enums;

enum WorkspaceSubscriptionDelivery: string
{
    case ACTIVATION_CODE = 'ACTIVATION_CODE';
    case DIRECT_ASSIGNMENT = 'DIRECT_ASSIGNMENT';
}
