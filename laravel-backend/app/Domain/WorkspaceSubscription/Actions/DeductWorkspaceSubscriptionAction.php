<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\Notifications\Actions\ScheduleAutomaticNotificationTaskAction;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionLedgerRepositoryInterface;
use App\Enums\WorkspaceLedgerReason;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\WorkspaceSubscription;

/**
 * Debit minutes from a workspace subscription.
 *
 * The caller MUST have already row-locked $subscription inside a transaction.
 * Writes the deduction ledger row in that same transaction.
 */
final readonly class DeductWorkspaceSubscriptionAction
{
    public function __construct(
        private WorkspaceSubscriptionLedgerRepositoryInterface $ledgers,
        private ScheduleAutomaticNotificationTaskAction $scheduledNotifications,
    ) {}

    public function handle(WorkspaceSubscription $subscription, int $minutes, ?string $visitId): int
    {
        if ($minutes <= 0) {
            return $subscription->remaining_minutes;
        }

        $before = $subscription->remaining_minutes;
        $after = max(0, $before - $minutes);
        $changed = $after - $before;   // negative

        $attributes = ['remaining_minutes' => $after];
        if ($after === 0) {
            $attributes['status'] = WorkspaceSubscriptionStatus::EXHAUSTED->value;
            $attributes['active_flag'] = null;
        }
        $subscription->update($attributes);

        $this->ledgers->append(
            $subscription,
            $changed,
            $after,
            WorkspaceLedgerReason::WORKSPACE_VISIT,
            $visitId,
        );

        if ($before >= 16 * 60 && $after > 0 && $after < 16 * 60) {
            $this->scheduledNotifications->workspaceSubscriptionLowHours($subscription);
        }

        return $after;
    }
}
