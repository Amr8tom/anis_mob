<?php

declare(strict_types=1);

namespace App\Enums;

enum WorkspaceStatus: string
{
    case OPEN = 'OPEN';
    case BUSY = 'BUSY';
    case FULL = 'FULL';
    case CLOSED = 'CLOSED';
}
