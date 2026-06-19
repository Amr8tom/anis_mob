<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use App\Support\SubscriptionSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Maps a User (+ active subscription) to the Flutter UserProfileModel (home header).
 *
 * @mixin User
 */
final class HomeProfileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $summary = SubscriptionSummary::forUser($this->resource);

        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'initials' => $this->initials ?? '',
            'subscriptionType' => $summary->tier,
            'subscriptionDaysRemaining' => $summary->daysRemaining,
            'subscriptionTotalDays' => $summary->totalDays,
            'subscriptionRemainingMinutes' => $summary->remainingMinutes,
            'subscriptionRemainingHours' => (int) floor($summary->remainingMinutes / 60),

            'totalStudyHours' => (int) $this->total_study_hours,
            'streakDays' => (int) $this->streak_days,
        ];
    }
}
