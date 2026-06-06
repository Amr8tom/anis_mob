<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Auth\Actions\LoginAction;
use App\Domain\Auth\Actions\LogoutAction;
use App\Domain\Auth\Actions\RegisterAction;
use App\Domain\Auth\Data\LoginData;
use App\Domain\Auth\Data\RegisterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $result = $action->handle(RegisterData::fromRequest($request));

        return ApiResponse::created([
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
        ], 'User created successfully');
    }

    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $result = $action->handle(LoginData::fromRequest($request));

        return ApiResponse::ok([
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
        ], 'Logged in');
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return ApiResponse::ok(new UserResource($user));
    }

    public function logout(Request $request, LogoutAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $action->handle($user);

        return ApiResponse::ok(null, 'Logged out');
    }
}
