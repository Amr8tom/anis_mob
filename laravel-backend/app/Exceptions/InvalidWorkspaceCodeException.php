<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InvalidWorkspaceCodeException extends ApiException
{
    protected int $status = 404;

    public function __construct(string $message = 'Invalid or expired workspace code.')
    {
        parent::__construct($message);
    }
}
