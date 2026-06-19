<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Repositories;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionLedgerRepositoryInterface;
use App\Enums\WorkspaceLedgerReason;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceSubscriptionLedger;

final class EloquentWorkspaceSubscriptionLedgerRepository implements WorkspaceSubscriptionLedgerRepositoryInterface
{
    public function append(
        WorkspaceSubscription $subscription,
        int $changeMinutes,
        int $balanceAfter,
        WorkspaceLedgerReason $reason,
        ?string $visitId = null,
    ): WorkspaceSubscriptionLedger {
        return WorkspaceSubscriptionLedger::create([
            'workspace_subscription_id' => $subscription->id,
            'workspace_visit_id' => $visitId,
            'workspace_id' => $subscription->workspace_id,
            'user_id' => $subscription->user_id,
            'change_minutes' => $changeMinutes,
            'balance_after' => $balanceAfter,
            'reason' => $reason,
        ]);
    }
}
