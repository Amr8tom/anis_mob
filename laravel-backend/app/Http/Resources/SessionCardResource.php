<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\StudySession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Maps a StudySession to the Flutter StudySessionModel (home card / workspace.sessions).
 *
 * @mixin StudySession
 */
final class SessionCardResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'university' => $this->whenLoaded('host', fn () => $this->host?->university ?? '', ''),
            'timeLabel' => $this->time_label ?? $this->start_time?->format('g:i A') ?? '',
            'tagLabel' => $this->tag_label ?? '',
            'tagColorKey' => $this->tag_color_key ?? 'blue',
            'status' => $this->status->toAppStudyStatus(),
            'participantCount' => (int) ($this->participants_count ?? 0),
            'maxParticipants' => (int) ($this->max_seats ?? 0),
        ];
    }
}
