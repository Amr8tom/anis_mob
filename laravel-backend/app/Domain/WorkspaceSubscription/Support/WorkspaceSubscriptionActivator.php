<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Support;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionLedgerRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Enums\WorkspaceLedgerReason;
use App\Enums\WorkspaceSubscriptionDelivery;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Exceptions\AlreadyHasWorkspaceSubscriptionException;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceSubscription;
use Illuminate\Database\QueryException;

/**
 * Creates an ACTIVE workspace subscription from a plan snapshot and writes the
 * grant ledger row. Shared by code redemption and direct phone assignment.
 *
 * MUST be called inside a DB transaction by the caller.
 */
final readonly class WorkspaceSubscriptionActivator
{
    public function __construct(
        private WorkspaceSubscriptionRepositoryInterface $subscriptions,
        private WorkspaceSubscriptionLedgerRepositoryInterface $ledgers,
    ) {}

    public function activate(
        WorkspacePlan $plan,
        string $userId,
        WorkspaceSubscriptionDelivery $delivery,
        ?string $issuedByOwnerId,
    ): WorkspaceSubscription {
        // R9: cannot activate while a usable active subscription exists here.
        if ($this->subscriptions->usableActiveForUserWorkspace($userId, $plan->workspace_id) !== null) {
            throw new AlreadyHasWorkspaceSubscriptionException;
        }

        return $this->createSubscription($plan, $delivery, $issuedByOwnerId, userId: $userId);
    }

    /**
     * Activate a workspace subscription for a walk-in visitor (no app account).
     * Only DIRECT_ASSIGNMENT — walk-ins can't redeem activation codes (no login).
     */
    public function activateForWalkIn(
        WorkspacePlan $plan,
        string $walkInId,
        WorkspaceSubscriptionDelivery $delivery,
        ?string $issuedByOwnerId,
    ): WorkspaceSubscription {
        if ($this->subscriptions->usableActiveForWalkInWorkspace($walkInId, $plan->workspace_id) !== null) {
            throw new AlreadyHasWorkspaceSubscriptionException;
        }

        return $this->createSubscription($plan, $delivery, $issuedByOwnerId, walkInId: $walkInId);
    }

    private function createSubscription(
        WorkspacePlan $plan,
        WorkspaceSubscriptionDelivery $delivery,
        ?string $issuedByOwnerId,
        ?string $userId = null,
        ?string $walkInId = null,
    ): WorkspaceSubscription {
        $reason = $delivery === WorkspaceSubscriptionDelivery::ACTIVATION_CODE
            ? WorkspaceLedgerReason::ACTIVATION
            : WorkspaceLedgerReason::DIRECT_ASSIGNMENT;

        try {
            $subscription = $this->subscriptions->create([
                'workspace_id' => $plan->workspace_id,
                'workspace_plan_id' => $plan->id,
                'user_id' => $userId,
                'walk_in_id' => $walkInId,
                'status' => WorkspaceSubscriptionStatus::ACTIVE->value,
                'active_flag' => 1,
                'started_at' => now(),
                'expires_at' => now()->addDays($plan->duration_days),
                'remaining_minutes' => $plan->included_minutes,
                'total_minutes' => $plan->included_minutes,
                'plan_name_snapshot' => $plan->name,
                'duration_days_snapshot' => $plan->duration_days,
                'price_cents_snapshot' => $plan->price_cents,
                'currency' => $plan->currency,
                'delivery_method' => $delivery->value,
                'issued_by_owner_id' => $issuedByOwnerId,
            ]);
        } catch (QueryException $e) {
            // unique(user_id|walk_in_id, workspace_id, active_flag) — concurrent activation.
            if (($e->errorInfo[1] ?? null) === 1062 || $e->getCode() === '23000') {
                throw new AlreadyHasWorkspaceSubscriptionException;
            }
            throw $e;
        }

        $this->ledgers->append(
            $subscription,
            $plan->included_minutes,
            $plan->included_minutes,
            $reason,
        );

        return $subscription;
    }
}
