<?php

declare(strict_types=1);

namespace App\Exceptions;

final class SessionFullException extends ApiException
{
    protected int $status = 409; // Conflict

    public function __construct(string $message = 'This session is already full.')
    {
        parent::__construct($message);
    }
}
