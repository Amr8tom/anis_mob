<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Attendance\Actions\CheckInAction;
use App\Domain\Attendance\Actions\CheckOutAction;
use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\CheckInRequest;
use App\Http\Resources\WorkspaceVisitResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class WorkspaceVisitController extends Controller
{
    public function checkIn(CheckInRequest $request, CheckInAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $visit = $action->handle($request->string('qr_payload')->value(), $user->id);

        return ApiResponse::created(new WorkspaceVisitResource($visit), 'Checked in');
    }

    public function checkOut(string $visit, Request $request, CheckOutAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $model = $action->handle($visit, $user->id);

        return ApiResponse::ok(new WorkspaceVisitResource($model), 'Checked out');
    }

    public function active(Request $request, AttendanceRepositoryInterface $visits): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $visit = $visits->activeVisitForUser($user->id);

        return ApiResponse::ok(
            $visit !== null ? new WorkspaceVisitResource($visit) : null,
            'Active visit',
        );
    }
}
