<?php

declare(strict_types=1);

namespace App\Http\Resources;

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
        ];
    }
}
