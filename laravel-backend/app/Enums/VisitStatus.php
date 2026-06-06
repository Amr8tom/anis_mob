<?php

declare(strict_types=1);

namespace App\Enums;

enum VisitStatus: string
{
    case CHECKED_IN = 'CHECKED_IN';
    case CHECKED_OUT = 'CHECKED_OUT';
}
