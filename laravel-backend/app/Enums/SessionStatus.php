<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionStatus: string
{
    case UPCOMING = 'UPCOMING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case ENDED = 'ENDED';
    case CANCELLED = 'CANCELLED';

    /**
     * Lifecycle label the Flutter app's StudySession parser expects.
     */
    public function toAppStudyStatus(): string
    {
        return match ($this) {
            self::UPCOMING => 'upcoming',
            self::IN_PROGRESS => 'inProgress',
            self::ENDED, self::CANCELLED => 'ended',
        };
    }
}
