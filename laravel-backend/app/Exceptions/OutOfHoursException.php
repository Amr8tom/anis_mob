<?php

declare(strict_types=1);

namespace App\Exceptions;

final class OutOfHoursException extends ApiException
{
    protected int $status = 403;

    public function __construct(string $message = 'Check-in is only allowed during workspace operating hours.')
    {
        parent::__construct($message);
    }
}
