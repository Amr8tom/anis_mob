<?php

declare(strict_types=1);

namespace App\Domain\Session\Actions;

use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Domain\Session\Data\CreateSessionData;
use App\Models\StudySession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class CreateBuddySessionAction
{
    public function __construct(private SessionRepositoryInterface $sessions) {}

    public function handle(CreateSessionData $data): StudySession
    {
        $session = DB::transaction(fn (): StudySession => $this->sessions->create($data));

        Log::info('buddy_session.created', ['session_id' => $session->id, 'host_id' => $data->hostId]);

        return $session->load(['host', 'workspace.drinks', 'participants'])->loadCount('participants');
    }
}
