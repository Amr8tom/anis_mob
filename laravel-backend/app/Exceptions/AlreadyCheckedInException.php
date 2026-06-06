<?php

declare(strict_types=1);

namespace App\Exceptions;

final class AlreadyCheckedInException extends ApiException
{
    protected int $status = 409; // Conflict

    public function __construct(string $message = 'You already have an active workspace visit.')
    {
        parent::__construct($message);
    }
}
