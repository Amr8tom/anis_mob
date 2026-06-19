<?php

declare(strict_types=1);

namespace App\Enums;

enum BillingSource: string
{
    case FREE = 'FREE';
    case GLOBAL_SUBSCRIPTION = 'GLOBAL_SUBSCRIPTION';
    case WORKSPACE_SUBSCRIPTION = 'WORKSPACE_SUBSCRIPTION';
}
