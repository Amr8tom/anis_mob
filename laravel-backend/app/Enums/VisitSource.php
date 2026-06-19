<?php

declare(strict_types=1);

namespace App\Enums;

enum VisitSource: string
{
    case QR = 'QR';        // app user scanned the workspace QR
    case OWNER = 'OWNER';  // registered manually by the workspace owner
}
