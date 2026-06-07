<?php

declare(strict_types=1);

namespace App\Domain\Session\Actions;

use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Enums\SessionStatus;
use App\Exceptions\AlreadyJoinedException;
use App\Exceptions\SessionFullException;
use App\Exceptions\SessionNotJoinableException;
use App\Models\StudySession;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class JoinBuddySessionAction
{
    public function __construct(private SessionRepositoryInterface $sessions) {}

    public function handle(string $sessionId, string $userId): StudySession
    {
        return DB::transaction(function () use ($sessionId, $userId): StudySession {
            // Lock the session row so concurrent joins can't exceed capacity.
            $session = $this->sessions->lockForUpdate($sessionId);

            if (in_array($session->status, [SessionStatus::ENDED, SessionStatus::CANCELLED], true)) {
                throw new SessionNotJoinableException('This session has ended or was cancelled.');
            }

            if ($session->start_time !== null && $session->start_time->isPast()) {
                throw new SessionNotJoinableException('This session has already started.');
            }

            if ($session->max_seats !== null && $this->sessions->participantCount($session) >= $session->max_seats) {
                throw new SessionFullException;
            }

            try {
                $this->sessions->attachParticipant($session, $userId);
            } catch (QueryException $e) {
                if ($this->isUniqueViolation($e)) {
                    throw new AlreadyJoinedException;
                }
                throw $e;
            }

            Log::info('buddy_session.joined', ['session_id' => $sessionId, 'user_id' => $userId]);

            return $session->load(['host', 'workspace.drinks', 'participants'])->loadCount('participants');
        });
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === 1062 || $e->getCode() === '23000';
    }
}
