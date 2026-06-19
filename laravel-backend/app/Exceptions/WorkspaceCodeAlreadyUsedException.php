<?php

declare(strict_types=1);

namespace App\Exceptions;

final class WorkspaceCodeAlreadyUsedException extends ApiException
{
    protected int $status = 409;

    public function __construct(string $message = 'This code has already been used or revoked.')
    {
        parent::__construct($message);
    }
}
