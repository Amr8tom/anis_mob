<?php

declare(strict_types=1);

namespace App\Domain\Profile\Repositories;

use App\Domain\Profile\Contracts\ProfileRepositoryInterface;
use App\Domain\Profile\Data\UpdateProfileData;
use App\Models\User;
use DateTimeInterface;

final class EloquentProfileRepository implements ProfileRepositoryInterface
{
    public function withDetails(string $userId): User
    {
        return User::query()
            ->with(['badges', 'activeSubscription.plan'])
            ->whereKey($userId)
            ->firstOrFail();
    }

    public function update(User $user, UpdateProfileData $data): User
    {
        $user->fill($data->toAttributes())->save();

        return $user->load(['badges', 'activeSubscription.plan']);
    }

    public function setProfileCompletedAt(User $user, ?DateTimeInterface $completedAt): User
    {
        $user->profile_completed_at = $completedAt;
        $user->save();

        return $user->load(['badges', 'activeSubscription.plan']);
    }
}
