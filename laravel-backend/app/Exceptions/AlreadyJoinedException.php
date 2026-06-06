<?php

declare(strict_types=1);

namespace App\Exceptions;

final class AlreadyJoinedException extends ApiException
{
    protected int $status = 409; // Conflict

    public function __construct(string $message = 'You have already joined this session.')
    {
        parent::__construct($message);
    }
}
