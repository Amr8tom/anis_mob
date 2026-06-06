<?php

declare(strict_types=1);

namespace App\Domain\Profile\Repositories;

use App\Domain\Profile\Contracts\ProfileRepositoryInterface;
use App\Models\User;

final class EloquentProfileRepository implements ProfileRepositoryInterface
{
    public function withDetails(string $userId): User
    {
        return User::query()
            ->with(['badges', 'activeSubscription.plan'])
            ->whereKey($userId)
            ->firstOrFail();
    }

    public function update(User $user, array $attributes): User
    {
        $user->fill($attributes)->save();

        return $user->load(['badges', 'activeSubscription.plan']);
    }
}
