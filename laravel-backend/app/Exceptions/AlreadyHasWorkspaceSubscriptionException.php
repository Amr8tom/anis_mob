<?php

declare(strict_types=1);

namespace App\Exceptions;

final class AlreadyHasWorkspaceSubscriptionException extends ApiException
{
    protected int $status = 409;

    public function __construct(string $message = 'You already have an active subscription at this workspace.')
    {
        parent::__construct($message);
    }
}
