<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionLedgerRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceLedgerReason;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Owner cancels an active workspace subscription. Forfeits remaining minutes.
 */
final readonly class CancelWorkspaceSubscriptionAction
{
    public function __construct(
        private WorkspaceSubscriptionRepositoryInterface $subscriptions,
        private WorkspaceSubscriptionLedgerRepositoryInterface $ledgers,
    ) {}

    public function handle(string $subscriptionId, string $workspaceId): WorkspaceSubscription
    {
        return DB::transaction(function () use ($subscriptionId, $workspaceId): WorkspaceSubscription {
            $subscription = $this->subscriptions->lockById($subscriptionId);
            if ($subscription === null || $subscription->workspace_id !== $workspaceId) {
                throw new NotFoundHttpException('Subscription not found.');
            }

            if ($subscription->status !== WorkspaceSubscriptionStatus::ACTIVE) {
                return $subscription;
            }

            $hasActiveVisit = WorkspaceVisit::where('workspace_subscription_id', $subscriptionId)
                ->where('status', VisitStatus::CHECKED_IN->value)
                ->exists();

            if ($hasActiveVisit) {
                throw ValidationException::withMessages([
                    'subscription' => 'لا يمكن إلغاء الاشتراك لأن هناك زائر مسجل الدخول باستخدامه حالياً. يرجى تسجيل خروجه أولاً.',
                ]);
            }

            $forfeited = $subscription->remaining_minutes;
            $subscription->update([
                'status' => WorkspaceSubscriptionStatus::CANCELLED->value,
                'active_flag' => null,
                'remaining_minutes' => 0,
            ]);

            if ($forfeited > 0) {
                $this->ledgers->append($subscription, -$forfeited, 0, WorkspaceLedgerReason::CANCELLATION);
            }

            return $subscription;
        });
    }
}
