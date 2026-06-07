<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Profile\Actions\UpdateProfileAction;
use App\Domain\Profile\Contracts\ProfileRepositoryInterface;
use App\Domain\Profile\Data\UpdateProfileData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProfileController extends Controller
{
    use AuthorizesRequests;

    public function show(Request $request, ProfileRepositoryInterface $profiles): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->authorize('viewProfile', $user);

        return ApiResponse::ok(new ProfileResource($profiles->withDetails($user->id)), 'Profile');
    }

    public function update(UpdateProfileRequest $request, UpdateProfileAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->authorize('updateProfile', $user);

        $updated = $action->handle($user, UpdateProfileData::fromRequest($request));

        return ApiResponse::ok(new ProfileResource($updated), 'Profile updated');
    }
}
