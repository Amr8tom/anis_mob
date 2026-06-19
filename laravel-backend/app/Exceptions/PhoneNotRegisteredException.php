<?php

declare(strict_types=1);

namespace App\Exceptions;

final class PhoneNotRegisteredException extends ApiException
{
    protected int $status = 422;

    public function __construct(string $message = 'No registered user with this phone number. Generate an activation code instead.')
    {
        parent::__construct($message);
    }
}
