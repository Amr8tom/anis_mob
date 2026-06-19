<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\WorkspaceSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WorkspaceSubscription
 */
final class WorkspaceSubscriptionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Discriminator so the app can tell workspace plans from global ones.
            'scope' => 'workspace',
            'workspaceId' => $this->workspace_id,
            'workspaceName' => $this->whenLoaded('workspace', fn () => $this->workspace?->name, null),
            'planName' => $this->plan_name_snapshot,
            'status' => strtolower($this->status->value),
            'remainingMinutes' => $this->remaining_minutes,
            'totalMinutes' => $this->total_minutes,
            'startedAt' => $this->started_at?->toIso8601String(),
            'expiresAt' => $this->expires_at?->toIso8601String(),
            'daysRemaining' => $this->daysLeft(),
            'message' => 'هذا الاشتراك صالح في هذه المساحة فقط.',
        ];
    }
}
