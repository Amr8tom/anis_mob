<?php

declare(strict_types=1);

namespace App\Exceptions;

final class WorkspaceClosedException extends ApiException
{
    protected int $status = 403;

    public function __construct(string $message = 'This workspace is currently closed.')
    {
        parent::__construct($message);
    }
}
