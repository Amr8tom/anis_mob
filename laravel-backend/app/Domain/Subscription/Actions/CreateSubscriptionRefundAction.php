<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Actions;

use App\Models\Subscription;
use App\Models\SubscriptionLedger;
use App\Models\SubscriptionRefund;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class CreateSubscriptionRefundAction
{
    public function handle(Subscription $subscription, ?WorkspaceVisit $visit, string $adminId, int $minutes, string $reason): SubscriptionRefund
    {
        return DB::transaction(function () use ($subscription, $visit, $adminId, $minutes, $reason): SubscriptionRefund {
            $subscription = Subscription::whereKey($subscription->id)->lockForUpdate()->firstOrFail();
            if ($subscription->remaining_minutes === null) {
                throw ValidationException::withMessages(['refunded_minutes' => 'Date-bound subscriptions do not have a minute balance to refund.']);
            }

            $after = $subscription->remaining_minutes + $minutes;
            $subscription->update(['remaining_minutes' => $after]);
            SubscriptionLedger::create([
                'subscription_id' => $subscription->id,
                'visit_id' => $visit?->id,
                'user_id' => $subscription->user_id,
                'change_minutes' => $minutes,
                'balance_after' => $after,
                'reason' => 'REFUND',
            ]);

            return SubscriptionRefund::create([
                'subscription_id' => $subscription->id,
                'workspace_visit_id' => $visit?->id,
                'created_by_admin_id' => $adminId,
                'refunded_minutes' => $minutes,
                'reason' => $reason,
            ]);
        });
    }
}
