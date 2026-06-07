<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Profile\Support\ProfileCompletionSummary;
use App\Models\Badge;
use App\Models\User;
use App\Support\SubscriptionSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Maps a User to the Flutter ProfileModel.
 *
 * @mixin User
 */
final class ProfileResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $summary = SubscriptionSummary::forUser($this->resource);
        $completion = ProfileCompletionSummary::forUser($this->resource);

        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'initials' => $this->initials ?? '',
            'email' => $this->email,
            'university' => $this->university ?? '',
            'studyField' => $this->study_field ?? '',
            'gender' => strtolower($this->gender?->value ?? ''),
            'interests' => $this->interests ?? [],
            'subscriptionType' => $summary->tier,
            'subscriptionDaysRemaining' => $summary->daysRemaining,
            'totalStudyHours' => (int) $this->total_study_hours,
            'streakDays' => (int) $this->streak_days,
            'totalSessions' => (int) $this->total_sessions,
            'badges' => $this->whenLoaded('badges', fn () => $this->badges->map(fn (Badge $badge): array => [
                'id' => $badge->id,
                'label' => $badge->label,
                'iconKey' => $badge->icon_key,
            ])->all(), []),
            // Public avatar URL until Flutter adds a dedicated avatarUrl field.
            'avatarPath' => $this->avatar_url,
            'profileCompleted' => $completion->completed,
            'profileCompletionPercentage' => $completion->percentage,
            'missingProfileFields' => $completion->missingFields,
        ];
    }
}
