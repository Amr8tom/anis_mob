<?php

declare(strict_types=1);

namespace App\Exceptions;

final class SessionNotJoinableException extends ApiException
{
    protected int $status = 409; // Conflict

    public function __construct(string $message = 'This session can no longer be joined.')
    {
        parent::__construct($message);
    }
}
