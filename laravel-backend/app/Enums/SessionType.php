<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionType: string
{
    case STUDY_GROUP = 'STUDY_GROUP';
    case EVENT = 'EVENT';
    case FOCUS_BLOCK = 'FOCUS_BLOCK';
}
