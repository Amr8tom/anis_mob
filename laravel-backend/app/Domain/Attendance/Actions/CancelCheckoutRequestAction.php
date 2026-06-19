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
 * A visitor withdraws a pending checkout request before the owner approves it.
 */
final readonly class CancelCheckoutRequestAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
    ) {}

    public function handle(string $visitId, string $userId): WorkspaceVisit
    {
        return DB::transaction(function () use ($visitId, $userId): WorkspaceVisit {
            $visit = $this->visits->lockVisitForUser($visitId, $userId);
            if ($visit === null) {
                throw new NotFoundHttpException('Visit not found.');
            }

            if ($visit->status === VisitStatus::CHECKED_OUT) {
                throw new VisitAlreadyClosedException;
            }

            // Idempotent: nothing to cancel.
            if ($visit->checkout_requested_at === null) {
                return $visit;
            }

            $visit = $this->visits->clearCheckoutRequest($visit);

            Log::info('visit.checkout_request_cancelled', [
                'visit_id' => $visit->id,
                'user_id' => $userId,
            ]);

            return $visit;
        });
    }
}
