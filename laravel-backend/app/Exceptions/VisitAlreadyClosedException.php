<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * A checkout request (or cancel) was attempted on a visit that is already
 * checked out.
 */
final class VisitAlreadyClosedException extends ApiException
{
    protected int $status = 409;

    public function __construct(string $message = 'This visit is already checked out.')
    {
        parent::__construct($message);
    }
}
