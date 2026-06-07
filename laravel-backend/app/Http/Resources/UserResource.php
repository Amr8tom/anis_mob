<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Profile\Support\ProfileCompletionSummary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $completion = ProfileCompletionSummary::forUser($this->resource);

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'whatsapp_number' => $this->whatsapp_number,
            'role' => $this->role?->value,
            'gender' => $this->gender?->value,
            'is_guest' => (bool) $this->is_guest,
            'avatar_url' => $this->avatar_url,
            'initials' => $this->initials,
            'university' => $this->university,
            'study_field' => $this->study_field,
            'interests' => $this->interests ?? [],
            'avatar_color_key' => $this->avatar_color_key,
            'availability' => $this->availability?->value,
            'rating' => (float) $this->rating,
            'wallet_balance' => (float) $this->wallet_balance,
            'total_study_hours' => $this->total_study_hours,
            'streak_days' => $this->streak_days,
            'total_sessions' => $this->total_sessions,
            'profile_completed' => $completion->completed,
            'profile_completion_percentage' => $completion->percentage,
            'missing_profile_fields' => $completion->missingFields,
        ];
    }
}
