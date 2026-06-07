<?php

declare(strict_types=1);

namespace App\Domain\Profile\Contracts;

use App\Domain\Profile\Data\UpdateProfileData;
use App\Models\User;
use DateTimeInterface;

interface ProfileRepositoryInterface
{
    public function withDetails(string $userId): User;

    public function update(User $user, UpdateProfileData $data): User;

    public function setProfileCompletedAt(User $user, ?DateTimeInterface $completedAt): User;
}
