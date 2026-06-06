<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Subscription\Contracts\PlanRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

final class PlanController extends Controller
{
    public function index(PlanRepositoryInterface $plans): JsonResponse
    {
        return ApiResponse::ok(PlanResource::collection($plans->activePlans()), 'Plans');
    }
}
