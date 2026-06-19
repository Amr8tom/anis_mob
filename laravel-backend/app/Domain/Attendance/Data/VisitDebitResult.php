<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Data;

final readonly class VisitDebitResult
{
    public function __construct(
        public int $deductedMinutes,
        public int $fundedRealMinutes,
    ) {}
}
