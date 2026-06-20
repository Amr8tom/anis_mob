<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Data\VisitFunding;
use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Enums\PlanTier;
use App\Models\Subscription;
use App\Models\Workspace;

/**
 * Decides which wallet funds a visit, in fixed priority:
 *   1. FREE when the workspace doesn't charge (multiplier 0).
 *   2. The user's usable workspace subscription at this workspace (>= 15 min).
 *   3. The user's usable global subscription.
 * Returns null when the workspace charges but no usable funding exists — the
 * caller decides which exception to throw.
 */
final readonly class ResolveVisitFundingAction
{
    private const MINIMUM_PAID_BALANCE_MINUTES = 15;

    public function __construct(
        private WorkspaceSubscriptionRepositoryInterface $workspaceSubscriptions,
        private SubscriptionRepositoryInterface $subscriptions,
    ) {}

    public function handle(Workspace $workspace, string $userId, bool $lockWorkspaceSubscription = false): ?VisitFunding
    {
        // 1. Free workspace — no balance consumed.
        if ((float) $workspace->hour_multiplier === 0.0) {
            return VisitFunding::free();
        }

        // 2. Workspace subscription takes priority at its own workspace.
        $workspaceSub = $lockWorkspaceSubscription
            ? $this->workspaceSubscriptions->lockUsableActiveForUserWorkspace($userId, $workspace->id)
            : $this->workspaceSubscriptions->usableActiveForUserWorkspace($userId, $workspace->id);
        if ($workspaceSub !== null && $workspaceSub->remaining_minutes >= self::MINIMUM_PAID_BALANCE_MINUTES) {
            return VisitFunding::workspace($workspaceSub->id);
        }

        // 3. Fall back to the global subscription.
        $global = $this->subscriptions->activeForUser($userId);
        if ($global !== null && $global->plan->tier !== PlanTier::FREE && $this->globalUsable($global)) {
            return VisitFunding::global($global->id, $global->plan->tier->value);
        }

        // Paid workspace with no usable funding.
        return null;
    }

    /**
     * Funding for a walk-in visitor. Walk-ins can only hold a workspace (special)
     * subscription — they have no app account, so no global subscription path.
     * Returns FREE when the workspace is free or the walk-in has no usable plan.
     */
    public function handleForWalkIn(Workspace $workspace, string $walkInId, bool $lockWorkspaceSubscription = false): VisitFunding
    {
        if ((float) $workspace->hour_multiplier === 0.0) {
            return VisitFunding::free();
        }

        $workspaceSub = $lockWorkspaceSubscription
            ? $this->workspaceSubscriptions->lockUsableActiveForWalkInWorkspace($walkInId, $workspace->id)
            : $this->workspaceSubscriptions->usableActiveForWalkInWorkspace($walkInId, $workspace->id);

        if ($workspaceSub !== null && $workspaceSub->remaining_minutes >= self::MINIMUM_PAID_BALANCE_MINUTES) {
            return VisitFunding::workspace($workspaceSub->id);
        }

        return VisitFunding::free();
    }

    private function globalUsable(Subscription $subscription): bool
    {
        if ($subscription->expires_at !== null && $subscription->expires_at->isPast()) {
            return false;
        }

        return $subscription->remaining_minutes === null
            || $subscription->remaining_minutes >= self::MINIMUM_PAID_BALANCE_MINUTES;
    }
}
