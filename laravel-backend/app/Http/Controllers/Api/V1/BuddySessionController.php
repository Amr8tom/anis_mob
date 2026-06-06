<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Session\Actions\CreateBuddySessionAction;
use App\Domain\Session\Actions\JoinBuddySessionAction;
use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Domain\Session\Data\CreateSessionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Buddy\CreateBuddySessionRequest;
use App\Http\Requests\Buddy\ListBuddySessionsRequest;
use App\Http\Resources\BuddySessionResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class BuddySessionController extends Controller
{
    public function index(ListBuddySessionsRequest $request, SessionRepositoryInterface $sessions): JsonResponse
    {
        $page = $sessions->paginateBuddy(
            $request->universityValue(),
            $request->subjectValue(),
            $request->filterValue(),
            $request->perPageValue(),
        );

        return ApiResponse::paginated(BuddySessionResource::collection($page), 'Buddy sessions');
    }

    public function show(string $session, SessionRepositoryInterface $sessions): JsonResponse
    {
        $model = $sessions->findBuddyWithDetails($session);

        return ApiResponse::ok(new BuddySessionResource($model), 'Buddy session');
    }

    public function store(CreateBuddySessionRequest $request, CreateBuddySessionAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $session = $action->handle(CreateSessionData::fromRequest($request, $user->id));

        return ApiResponse::created(new BuddySessionResource($session), 'Buddy session created');
    }

    public function join(string $session, Request $request, JoinBuddySessionAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $model = $action->handle($session, $user->id);

        return ApiResponse::ok(new BuddySessionResource($model), 'Joined session');
    }
}
