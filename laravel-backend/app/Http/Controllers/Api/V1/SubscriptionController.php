<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class SubscriptionController extends Controller
{
    public function current(Request $request, SubscriptionRepositoryInterface $subscriptions): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $subscription = $subscriptions->activeForUser($user->id);

        return ApiResponse::ok(
            $subscription !== null ? new SubscriptionResource($subscription) : null,
            'Current subscription',
        );
    }
}
