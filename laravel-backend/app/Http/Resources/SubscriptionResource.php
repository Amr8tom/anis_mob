<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Subscription;
use App\Support\SubscriptionSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Subscription
 */
final class SubscriptionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $summary = SubscriptionSummary::fromSubscription($this->resource);

        return [
            'id' => $this->id,
            // Discriminator: 'global' vs 'workspace' (workspace-scoped plans).
            'scope' => 'global',
            'planName' => $this->plan?->name,
            'subscriptionType' => $summary->tier,
            'status' => strtolower($this->status->value),
            'startedAt' => $this->started_at?->toIso8601String(),
            'expiresAt' => $this->expires_at?->toIso8601String(),
            'remainingMinutes' => $this->remaining_minutes,
            'subscriptionDaysRemaining' => $summary->daysRemaining,
            'subscriptionTotalDays' => $summary->totalDays,
        ];
    }
}
