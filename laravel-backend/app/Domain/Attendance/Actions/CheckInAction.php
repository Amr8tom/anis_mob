<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Enums\WorkspaceStatus;
use App\Exceptions\AlreadyCheckedInException;
use App\Exceptions\InvalidQrCodeException;
use App\Exceptions\NoActiveSubscriptionException;
use App\Exceptions\OutOfHoursException;
use App\Exceptions\WorkspaceClosedException;
use App\Exceptions\WorkspaceFullException;
use App\Models\WorkspaceVisit;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class CheckInAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
        private ResolveVisitFundingAction $funding,
    ) {}

    public function handle(string $qrToken, string $userId): WorkspaceVisit
    {
        return DB::transaction(function () use ($qrToken, $userId): WorkspaceVisit {
            // Resolve the workspace securely from the QR token (never a client id).
            $workspace = $this->visits->activeWorkspaceByQrToken($qrToken);
            if ($workspace === null) {
                throw new InvalidQrCodeException;
            }

            if ($workspace->status->value === WorkspaceStatus::CLOSED->value) {
                throw new WorkspaceClosedException;
            }

            if ($workspace->status->value === WorkspaceStatus::FULL->value) {
                throw new WorkspaceFullException;
            }

            if ($workspace->open_time && $workspace->close_time) {
                $now = now()->format('H:i:s');
                $open = $workspace->open_time;
                $close = $workspace->close_time;

                $isOpen = $open <= $close
                    ? ($now >= $open && $now <= $close)
                    : ($now >= $open || $now <= $close);

                if (! $isOpen) {
                    throw new OutOfHoursException;
                }
            }

            // Pick the funding wallet: workspace subscription first, then global,
            // then free. Null means the workspace charges but nothing can fund it.
            $funding = $this->funding->handle($workspace, $userId, lockWorkspaceSubscription: true);
            if ($funding === null) {
                throw new NoActiveSubscriptionException;
            }

            if ($this->visits->activeVisitForUser($userId) !== null) {
                throw new AlreadyCheckedInException;
            }

            if (! $this->visits->reserveOccupancy($workspace)) {
                throw new WorkspaceFullException;
            }

            try {
                $visit = $this->visits->createCheckIn($userId, $workspace->id, $funding);
            } catch (QueryException $e) {
                // Unique(user_id, active_flag) — a concurrent check-in beat us to it.
                if ($this->isUniqueViolation($e)) {
                    throw new AlreadyCheckedInException;
                }
                throw $e;
            }

            Log::info('visit.checked_in', ['visit_id' => $visit->id, 'user_id' => $userId]);

            return $visit;
        });
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === 1062 || $e->getCode() === '23000';
    }
}
