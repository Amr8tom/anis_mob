<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\SessionStatus;
use App\Models\StudySession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Maps a StudySession to the Flutter BuddySessionModel (camelCase).
 *
 * @mixin StudySession
 */
final class BuddySessionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $host = $this->host;
        $count = (int) ($this->participants_count ?? $this->participants->count());

        return [
            'id' => $this->id,
            'buddyName' => $host?->full_name ?? '',
            'buddyInitials' => $host?->initials ?? '',
            'avatarColorKey' => $host?->avatar_color_key ?? 'blue',
            'university' => $host?->university ?? '',
            'availability' => strtolower($host?->availability?->value ?? 'offline'),
            'topic' => $this->title,
            'subject' => $this->subject ?? '',
            'description' => $this->description ?? '',
            'rules' => $this->rules ?? [],
            'gift' => $this->gift,
            'members' => $this->whenLoaded('participants', fn () => $this->mapMembers()),
            'maxCapacity' => (int) ($this->max_seats ?? 0),
            'startTime' => $this->start_time?->toIso8601String(),
            'timeLabel' => $this->time_label ?? $this->start_time?->format('g:i A') ?? '',
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),
            'sessionStatus' => $this->sessionStatus($count),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mapMembers(): array
    {
        return $this->participants->map(fn (User $member): array => [
            'id' => $member->id,
            'name' => $member->full_name,
            'initials' => $member->initials ?? '',
            'avatarColorKey' => $member->avatar_color_key ?? 'blue',
            'university' => $member->university ?? '',
            'studyField' => $member->study_field ?? '',
            'interests' => $member->interests ?? [],
            'rating' => (float) $member->rating,
            'totalSessions' => (int) $member->total_sessions,
            'isFounder' => $member->id === $this->host_id,
        ])->all();
    }

    private function sessionStatus(int $count): string
    {
        if ($this->status === SessionStatus::IN_PROGRESS) {
            return 'inProgress';
        }

        if ($this->max_seats !== null && $count >= $this->max_seats) {
            return 'full';
        }

        return 'open';
    }
}
