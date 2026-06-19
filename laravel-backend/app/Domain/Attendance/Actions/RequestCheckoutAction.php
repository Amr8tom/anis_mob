<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Enums\VisitStatus;
use App\Exceptions\VisitAlreadyClosedException;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * A visitor asks the workspace owner to check them out (approval-mode workspaces).
 *
 * This records a request only — NO balance is touched here. Minutes are deducted
 * later, when the owner approves via OwnerCheckOutVisitAction (the real checkout).
 */
final readonly class RequestCheckoutAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
    ) {}

    public function handle(string $visitId, string $userId, ?string $note = null): WorkspaceVisit
    {
        return DB::transaction(function () use ($visitId, $userId, $note): WorkspaceVisit {
            // Row-locked AND ownership-scoped: a user can only request checkout for
            // their own visit.
            $visit = $this->visits->lockVisitForUser($visitId, $userId);
            if ($visit === null) {
                throw new NotFoundHttpException('Visit not found.');
            }

            if ($visit->status === VisitStatus::CHECKED_OUT) {
                throw new VisitAlreadyClosedException;
            }

            // Idempotent: re-requesting returns the existing pending request.
            if ($visit->checkout_requested_at !== null) {
                return $visit;
            }

            $visit = $this->visits->markCheckoutRequested($visit, $note);

            Log::info('visit.checkout_requested', [
                'visit_id' => $visit->id,
                'user_id' => $userId,
            ]);

            return $visit;
        });
    }
}
