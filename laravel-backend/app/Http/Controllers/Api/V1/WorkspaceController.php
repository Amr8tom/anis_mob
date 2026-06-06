<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Workspace\Contracts\WorkspaceRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\ListWorkspacesRequest;
use App\Http\Resources\WorkspaceResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class WorkspaceController extends Controller
{
    public function index(ListWorkspacesRequest $request, WorkspaceRepositoryInterface $workspaces): JsonResponse
    {
        $page = $workspaces->paginateActive(
            $request->filterValue(),
            $request->latitudeValue(),
            $request->longitudeValue(),
            $request->perPageValue(),
        );

        return ApiResponse::paginated(WorkspaceResource::collection($page), 'Workspaces');
    }

    public function show(string $workspace, Request $request, WorkspaceRepositoryInterface $workspaces): JsonResponse
    {
        $latitude = $request->filled('latitude') ? (float) $request->input('latitude') : null;
        $longitude = $request->filled('longitude') ? (float) $request->input('longitude') : null;

        $model = $workspaces->findActiveWithDetails($workspace, $latitude, $longitude);

        return ApiResponse::ok(new WorkspaceResource($model), 'Workspace');
    }
}
