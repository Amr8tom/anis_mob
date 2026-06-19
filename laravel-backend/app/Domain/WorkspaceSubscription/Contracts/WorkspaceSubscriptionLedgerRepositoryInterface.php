<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Contracts;

use App\Enums\WorkspaceLedgerReason;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceSubscriptionLedger;

interface WorkspaceSubscriptionLedgerRepositoryInterface
{
    public function append(
        WorkspaceSubscription $subscription,
        int $changeMinutes,
        int $balanceAfter,
        WorkspaceLedgerReason $reason,
        ?string $visitId = null,
    ): WorkspaceSubscriptionLedger;
}
