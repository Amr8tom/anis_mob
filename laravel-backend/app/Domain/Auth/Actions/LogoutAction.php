<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

final readonly class LogoutAction
{
    /**
     * Revoke the token used for the current request.
     */
    public function handle(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }
    }
}
