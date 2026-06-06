<?php

declare(strict_types=1);

namespace App\Domain\Profile\Contracts;

use App\Models\User;

interface ProfileRepositoryInterface
{
    public function withDetails(string $userId): User;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(User $user, array $attributes): User;
}
