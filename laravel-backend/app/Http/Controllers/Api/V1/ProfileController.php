<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Profile\Contracts\ProfileRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProfileController extends Controller
{
    public function show(Request $request, ProfileRepositoryInterface $profiles): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return ApiResponse::ok(new ProfileResource($profiles->withDetails($user->id)), 'Profile');
    }

    public function update(UpdateProfileRequest $request, ProfileRepositoryInterface $profiles): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $updated = $profiles->update($user, $request->attributesForUpdate());

        return ApiResponse::ok(new ProfileResource($updated), 'Profile updated');
    }
}
