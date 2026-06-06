<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Contracts\AuthRepositoryInterface;
use App\Domain\Auth\Data\RegisterData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class RegisterAction
{
    public function __construct(private AuthRepositoryInterface $users) {}

    /**
     * @return array{user: User, token: string}
     */
    public function handle(RegisterData $data): array
    {
        return DB::transaction(function () use ($data): array {
            $user = $this->users->create($data);
            $token = $user->createToken('mobile')->plainTextToken;

            Log::info('auth.registered', ['user_id' => $user->id]);

            return ['user' => $user, 'token' => $token];
        });
    }
}
