<?php

declare(strict_types=1);

namespace App\Exceptions;

final class WorkspaceFullException extends ApiException
{
    protected int $status = 409;

    public function __construct(string $message = 'This workspace is currently at full capacity.')
    {
        parent::__construct($message);
    }
}
