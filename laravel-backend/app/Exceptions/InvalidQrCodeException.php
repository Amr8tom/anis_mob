<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InvalidQrCodeException extends ApiException
{
    protected int $status = 404;

    public function __construct(string $message = 'Invalid or unknown workspace QR code.')
    {
        parent::__construct($message);
    }
}
