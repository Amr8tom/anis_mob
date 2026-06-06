<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\HomeProfileResource;
use App\Http\Resources\SessionCardResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class HomeController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $user->loadMissing('activeSubscription.plan');

        return ApiResponse::ok(new HomeProfileResource($user), 'Home profile');
    }

    public function todaySessions(Request $request, SessionRepositoryInterface $sessions): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', 20) ?: 20, 100);
        $page = $sessions->todaySessions($perPage);

        return ApiResponse::paginated(SessionCardResource::collection($page), "Today's sessions");
    }
}
