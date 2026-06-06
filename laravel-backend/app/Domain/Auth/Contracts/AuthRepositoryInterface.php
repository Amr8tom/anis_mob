<?php

declare(strict_types=1);

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\Data\RegisterData;
use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * Find a user by their login identifier — phone number OR email.
     */
    public function findByLogin(string $identifier): ?User;

    public function create(RegisterData $data): User;
}
