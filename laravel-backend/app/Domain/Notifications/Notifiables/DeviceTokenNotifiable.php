<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Notifiables;

use App\Models\DeviceToken;
use Illuminate\Notifications\Notifiable;

final class DeviceTokenNotifiable
{
    use Notifiable;

    public function __construct(public readonly DeviceToken $deviceToken)
    {
    }

    public function routeNotificationForFcm(): string
    {
        return $this->deviceToken->token;
    }

    public function getKey(): string
    {
        return $this->deviceToken->id;
    }
}
