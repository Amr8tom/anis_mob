<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Subscription\Actions\ActivatePlanCodeAction;
use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Domain\Subscription\Data\ActivatePlanCodeData;
use App\Domain\WorkspaceSubscription\Actions\RedeemWorkspaceSubscriptionCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\ActivatePlanCodeRequest;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\WorkspaceSubscriptionResource;
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

    public function activate(
        ActivatePlanCodeRequest $request,
        ActivatePlanCodeAction $action,
        RedeemWorkspaceSubscriptionCodeAction $workspaceRedeem,
    ): JsonResponse {
        /** @var User $user */
        $user = $request->user();

        $code = strtoupper(trim((string) $request->string('code')));

        // Workspace-scoped codes are prefixed WSP-; everything else is a global code.
        if (str_starts_with($code, 'WSP-')) {
            $workspaceSubscription = $workspaceRedeem->handle($code, $user->id);

            return ApiResponse::ok(
                new WorkspaceSubscriptionResource($workspaceSubscription),
                'Subscription activated successfully',
            );
        }

        $subscription = $action->handle($user, ActivatePlanCodeData::fromRequest($request));

        return ApiResponse::ok(
            new SubscriptionResource($subscription),
            'Subscription activated successfully',
        );
    }
}
