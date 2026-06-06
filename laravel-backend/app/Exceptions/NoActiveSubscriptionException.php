<?php

declare(strict_types=1);

namespace App\Exceptions;

final class NoActiveSubscriptionException extends ApiException
{
    protected int $status = 402; // Payment Required

    public function __construct(string $message = 'No active subscription or insufficient balance.')
    {
        parent::__construct($message);
    }
}
