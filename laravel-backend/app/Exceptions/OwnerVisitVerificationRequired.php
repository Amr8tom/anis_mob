<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\BillingSource;
use RuntimeException;

final class OwnerVisitVerificationRequired extends RuntimeException
{
    public function __construct(
        public readonly string $phoneNumber,
        public readonly string $visitorName,
        public readonly BillingSource $billingSource,
    ) {
        parent::__construct('يجب أن يؤكد الزائر دخوله باستخدام كلمة مرور حسابه.');
    }
}
