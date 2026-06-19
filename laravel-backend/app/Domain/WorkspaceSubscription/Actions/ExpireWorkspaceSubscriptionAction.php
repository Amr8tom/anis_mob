<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionLedgerRepositoryInterface;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceLedgerReason;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceVisit;

/**
 * Expire a single subscription: forfeit remaining minutes, flip to EXPIRED.
 *
 * The caller MUST have row-locked $subscription inside a transaction. Idempotent.
 */
final readonly class ExpireWorkspaceSubscriptionAction
{
    public function __construct(private WorkspaceSubscriptionLedgerRepositoryInterface $ledgers) {}

    public function handle(WorkspaceSubscription $subscription): WorkspaceSubscription
    {
        if (! in_array($subscription->status, [
            WorkspaceSubscriptionStatus::ACTIVE,
            WorkspaceSubscriptionStatus::EXHAUSTED,
        ], true)) {
            return $subscription;   // already closed — idempotent
        }

        $hasActiveVisit = WorkspaceVisit::where('workspace_subscription_id', $subscription->id)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->exists();

        if ($hasActiveVisit) {
            // Defer expiration until the active visit is completed
            return $subscription;
        }

        $forfeited = $subscription->remaining_minutes;

        $subscription->update([
            'status' => WorkspaceSubscriptionStatus::EXPIRED->value,
            'active_flag' => null,
            'remaining_minutes' => 0,
        ]);

        if ($forfeited > 0) {
            $this->ledgers->append(
                $subscription,
                -$forfeited,
                0,
                WorkspaceLedgerReason::EXPIRY_FORFEIT,
            );
        }

        return $subscription;
    }
}
