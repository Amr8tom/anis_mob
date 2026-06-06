<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\Contracts\AuthRepositoryInterface;
use App\Domain\Auth\Data\RegisterData;
use App\Enums\Availability;
use App\Enums\UserRole;
use App\Models\User;

final class EloquentAuthRepository implements AuthRepositoryInterface
{
    public function findByLogin(string $identifier): ?User
    {
        return User::query()
            ->where('phone_number', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }

    public function create(RegisterData $data): User
    {
        return User::create([
            'full_name' => $data->fullName,
            'phone_number' => $data->phoneNumber,
            'email' => $data->email,
            'whatsapp_number' => $data->whatsappNumber,
            'password' => $data->password,            // hashed by the model cast
            'role' => UserRole::USER,
            'gender' => $data->gender,
            'study_field' => $data->studyField,
            'is_guest' => false,
            'initials' => $this->initialsFrom($data->fullName),
            'avatar_color_key' => 'blue',
            'availability' => Availability::OFFLINE,
        ]);
    }

    private function initialsFrom(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        $letters = array_map(static fn (string $p): string => mb_substr($p, 0, 1), array_slice($parts, 0, 2));

        return mb_strtoupper(implode('', $letters)) ?: '؟';
    }
}
