<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Attendance\Actions\CancelCheckoutRequestAction;
use App\Domain\Attendance\Actions\CheckInAction;
use App\Domain\Attendance\Actions\CheckOutAction;
use App\Domain\Attendance\Actions\RequestCheckoutAction;
use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use App\Exceptions\CheckoutRequiresApprovalException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\CheckInRequest;
use App\Http\Resources\WorkspaceVisitResource;
use App\Models\User;
use App\Models\WorkspaceVisit;
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

        // Guard: paid visitors cannot self-checkout at approval-mode workspaces.
        // They must send a checkout request for the owner to approve. Free visits
        // and direct-mode workspaces fall through to the normal checkout.
        $pending = WorkspaceVisit::query()
            ->with('workspace')
            ->where('id', $visit)
            ->where('user_id', $user->id)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->first();

        if (
            $pending !== null
            && $pending->billing_source !== BillingSource::FREE
            && $pending->workspace?->requiresCheckoutApproval()
        ) {
            throw new CheckoutRequiresApprovalException;
        }

        $model = $action->handle($visit, $user->id);

        return ApiResponse::ok(new WorkspaceVisitResource($model), 'Checked out');
    }

    /**
     * Paid visitor asks the owner to check them out (approval-mode workspaces).
     * Free visits and direct-mode workspaces are checked out immediately instead,
     * so the client's single "check out" button always does the right thing.
     */
    public function requestCheckout(
        string $visit,
        Request $request,
        RequestCheckoutAction $requestAction,
        CheckOutAction $checkOutAction,
    ): JsonResponse {
        /** @var User $user */
        $user = $request->user();

        $active = WorkspaceVisit::query()
            ->with('workspace')
            ->where('id', $visit)
            ->where('user_id', $user->id)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->first();

        $needsApproval = $active !== null
            && $active->billing_source !== BillingSource::FREE
            && $active->workspace?->requiresCheckoutApproval();

        if (! $needsApproval) {
            // Direct path: behave exactly like a normal checkout.
            $model = $checkOutAction->handle($visit, $user->id);

            return ApiResponse::ok(new WorkspaceVisitResource($model), 'Checked out');
        }

        $note = $request->input('note') !== null ? trim((string) $request->input('note')) : null;
        $model = $requestAction->handle($visit, $user->id, $note ?: null);

        return ApiResponse::ok(new WorkspaceVisitResource($model->load('workspace')), 'Checkout requested');
    }

    public function cancelCheckoutRequest(string $visit, Request $request, CancelCheckoutRequestAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $model = $action->handle($visit, $user->id);

        return ApiResponse::ok(new WorkspaceVisitResource($model->load('workspace')), 'Checkout request cancelled');
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
