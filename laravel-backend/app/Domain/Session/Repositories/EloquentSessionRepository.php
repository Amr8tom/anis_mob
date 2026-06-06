<?php

declare(strict_types=1);

namespace App\Domain\Session\Repositories;

use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Domain\Session\Data\CreateSessionData;
use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\StudySession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class EloquentSessionRepository implements SessionRepositoryInterface
{
    public function todaySessions(int $perPage): LengthAwarePaginator
    {
        return StudySession::query()
            ->with('host')
            ->withCount('participants')
            ->whereDate('start_time', today())
            ->whereIn('status', [SessionStatus::UPCOMING->value, SessionStatus::IN_PROGRESS->value])
            ->orderBy('start_time')
            ->paginate($perPage);
    }

    public function paginateBuddy(?string $university, ?string $subject, ?string $filter, int $perPage): LengthAwarePaginator
    {
        return StudySession::query()
            ->with(['host', 'workspace.drinks', 'participants'])
            ->withCount('participants')
            ->when($university, fn (Builder $q, string $u) => $q->whereHas('host', fn (Builder $h) => $h->where('university', $u)))
            ->when($subject, fn (Builder $q, string $s) => $q->where('subject', $s))
            ->when($filter === 'open', fn (Builder $q) => $q->where('status', SessionStatus::UPCOMING->value))
            ->when($filter === 'availableNow', fn (Builder $q) => $q->whereHas('host', fn (Builder $h) => $h->where('availability', 'ONLINE')))
            ->orderBy('start_time')
            ->paginate($perPage);
    }

    public function findBuddyWithDetails(string $id): StudySession
    {
        return StudySession::query()
            ->with(['host', 'workspace.drinks', 'participants'])
            ->withCount('participants')
            ->whereKey($id)
            ->firstOrFail();
    }

    public function create(CreateSessionData $data): StudySession
    {
        return StudySession::create([
            'workspace_id' => $data->workspaceId,
            'host_id' => $data->hostId,
            'title' => $data->topic,
            'subject' => $data->subject,
            'description' => $data->description,
            'rules' => $data->rules,
            'gift' => $data->gift,
            'type' => SessionType::STUDY_GROUP,
            'status' => SessionStatus::UPCOMING,
            'start_time' => $data->startTime,
            'end_time' => $data->startTime->addHours(2),
            'max_seats' => $data->maxCapacity,
            'time_label' => $data->startTime->format('g:i A'),
        ]);
    }

    public function lockForUpdate(string $id): StudySession
    {
        return StudySession::query()->whereKey($id)->lockForUpdate()->firstOrFail();
    }

    public function participantCount(StudySession $session): int
    {
        return $session->participants()->count();
    }

    public function attachParticipant(StudySession $session, string $userId): void
    {
        $session->participants()->attach($userId, ['joined_at' => now()]);
    }
}
