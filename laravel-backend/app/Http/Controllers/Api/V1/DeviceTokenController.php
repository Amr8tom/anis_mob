<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceToken\StoreDeviceTokenRequest;
use App\Models\DeviceToken;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DeviceTokenController extends Controller
{
    /**
     * Register (or refresh) the calling device's FCM token for the authenticated user.
     * Idempotent: re-binds an existing token to this user and bumps last_used_at.
     */
    public function store(StoreDeviceTokenRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        DeviceToken::updateOrCreate(
            ['token' => $request->string('token')->value()],
            [
                'user_id' => $user->id,
                'platform' => $request->input('platform'),
                'last_used_at' => now(),
            ],
        );

        return ApiResponse::ok(null, 'Device token registered');
    }

    /**
     * Unregister a device token (call on logout). Only the owner can delete their token.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string', 'max:512'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->devices()
            ->where('token', $request->string('token')->value())
            ->delete();

        return ApiResponse::ok(null, 'Device token removed');
    }
}
