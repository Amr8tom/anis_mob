<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\BillingSource;
use App\Enums\CheckoutMode;
use App\Enums\VisitStatus;
use App\Models\WorkspaceVisit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Maps a WorkspaceVisit to the Flutter WorkspaceAttendanceModel (snake_case).
 *
 * @mixin WorkspaceVisit
 */
final class WorkspaceVisitResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mode = $this->whenLoaded('workspace', fn () => $this->workspace?->checkout_mode, CheckoutMode::DIRECT);
        $modeValue = $mode instanceof CheckoutMode ? $mode : CheckoutMode::DIRECT;
        $billingSource = $this->billing_source ?? BillingSource::FREE;
        $isFree = $billingSource === BillingSource::FREE;

        // When workspace-funded, surface that wallet's balance for the app.
        $workspaceSub = $this->whenLoaded('workspaceSubscription', fn () => $this->workspaceSubscription, null);

        return [
            'attendance_id' => $this->id,
            'workspace_id' => $this->workspace_id,
            'workspace_name' => $this->whenLoaded('workspace', fn () => $this->workspace?->name ?? '', ''),
            'check_in_time' => $this->check_in_at?->toIso8601String(),
            'check_out_time' => $this->check_out_at?->toIso8601String(),
            'study_minutes' => $this->duration_minutes,
            'billable_minutes' => $this->billable_minutes,
            'deducted_minutes' => $this->deducted_minutes,
            'hour_multiplier_applied' => $this->hour_multiplier_applied !== null ? (float) $this->hour_multiplier_applied : null,

            // Funding source for this visit.
            'billing_source' => $billingSource->value,
            'workspace_subscription_remaining_minutes' => $workspaceSub?->remaining_minutes,
            'workspace_subscription_expires_at' => $workspaceSub?->expires_at?->toIso8601String(),

            // Checkout-approval flow
            'checkout_mode' => $modeValue->value,
            'checkout_requested_at' => $this->checkout_requested_at?->toIso8601String(),
            'is_checkout_pending' => $this->status === VisitStatus::CHECKED_IN && $this->checkout_requested_at !== null,
            // The app uses this to decide between "check out" and "request to leave".
            'can_check_out_directly' => $isFree || $modeValue === CheckoutMode::DIRECT,
        ];
    }
}
