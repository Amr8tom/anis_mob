<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InvalidCredentialsException extends ApiException
{
    protected int $status = 401; // Unauthorized

    public function __construct(string $message = 'Invalid phone number or password.')
    {
        parent::__construct($message);
    }
}
