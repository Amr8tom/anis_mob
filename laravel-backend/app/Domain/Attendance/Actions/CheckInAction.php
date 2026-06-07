<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Enums\PlanTier;
use App\Exceptions\AlreadyCheckedInException;
use App\Exceptions\InvalidQrCodeException;
use App\Exceptions\NoActiveSubscriptionException;
use App\Models\Subscription;
use App\Models\WorkspaceVisit;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class CheckInAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
        private SubscriptionRepositoryInterface $subscriptions,
    ) {}

    public function handle(string $qrToken, string $userId): WorkspaceVisit
    {
        return DB::transaction(function () use ($qrToken, $userId): WorkspaceVisit {
            // Resolve the workspace securely from the QR token (never a client id).
            $workspace = $this->visits->findActiveWorkspaceByQrToken($qrToken);
            if ($workspace === null) {
                throw new InvalidQrCodeException;
            }

            $subscription = $this->subscriptions->activeForUser($userId);
            if ($workspace->hour_multiplier > 0.0) {
                if ($subscription === null || $subscription->plan->tier === PlanTier::FREE) {
                    throw new NoActiveSubscriptionException;
                }
                if (! $this->isUsable($subscription)) {
                    throw new NoActiveSubscriptionException;
                }
            }

            if ($this->visits->activeVisitForUser($userId) !== null) {
                throw new AlreadyCheckedInException;
            }

            try {
                $visit = $this->visits->createCheckIn($userId, $workspace->id, $subscription?->id);
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

    private function isUsable(?Subscription $subscription): bool
    {
        if ($subscription === null) {
            return false;
        }

        if ($subscription->expires_at !== null && $subscription->expires_at->isPast()) {
            return false;
        }

        return $subscription->remaining_minutes === null || $subscription->remaining_minutes > 0;
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === 1062 || $e->getCode() === '23000';
    }
}
