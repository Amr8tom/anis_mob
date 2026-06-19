<?php

declare(strict_types=1);

namespace App\Exceptions;

final class RoomSlotUnavailableException extends ApiException
{
    protected int $status = 409;

    public function __construct(string $message = 'هذه الغرفة محجوزة بالفعل في هذا الوقت.')
    {
        parent::__construct($message);
    }
}
