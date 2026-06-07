<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    public function viewProfile(User $actor, User $profile): bool
    {
        return $actor->is($profile);
    }

    public function updateProfile(User $actor, User $profile): bool
    {
        return $actor->is($profile);
    }
}
