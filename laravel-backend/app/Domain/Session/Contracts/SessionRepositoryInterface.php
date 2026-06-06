<?php

declare(strict_types=1);

namespace App\Domain\Session\Contracts;

use App\Domain\Session\Data\CreateSessionData;
use App\Models\StudySession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SessionRepositoryInterface
{
    /**
     * Sessions starting today (home widget).
     *
     * @return LengthAwarePaginator<int, StudySession>
     */
    public function todaySessions(int $perPage): LengthAwarePaginator;

    /**
     * Buddy session listing with optional filters.
     *
     * @return LengthAwarePaginator<int, StudySession>
     */
    public function paginateBuddy(?string $university, ?string $subject, ?string $filter, int $perPage): LengthAwarePaginator;

    public function findBuddyWithDetails(string $id): StudySession;

    public function create(CreateSessionData $data): StudySession;

    /**
     * Row-locked fetch for capacity checks inside a transaction.
     */
    public function lockForUpdate(string $id): StudySession;

    public function participantCount(StudySession $session): int;

    public function attachParticipant(StudySession $session, string $userId): void;
}
