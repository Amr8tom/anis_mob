<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Contracts\AuthRepositoryInterface;
use App\Domain\Auth\Data\LoginData;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

final readonly class LoginAction
{
    public function __construct(private AuthRepositoryInterface $users) {}

    /**
     * @return array{user: User, token: string}
     */
    public function handle(LoginData $data): array
    {
        $user = $this->users->findByLogin($data->identifier);

        if ($user === null || ! Hash::check($data->password, $user->password)) {
            // Never log raw phone/email/password — only a non-identifying event.
            Log::warning('auth.login_failed');
            throw new InvalidCredentialsException();
        }

        $token = $user->createToken('mobile')->plainTextToken;

        Log::info('auth.logged_in', ['user_id' => $user->id]);

        return ['user' => $user, 'token' => $token];
    }
}
