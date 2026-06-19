<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Actions;

use App\Domain\Subscription\Data\ActivatePlanCodeData;
use App\Enums\SubscriptionStatus;
use App\Models\PlanActivationCode;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class ActivatePlanCodeAction
{
    public function handle(User $user, ActivatePlanCodeData $data): Subscription
    {
        return DB::transaction(function () use ($user, $data) {
            $activationCode = PlanActivationCode::where('code', $data->code)
                ->lockForUpdate()
                ->firstOrFail();

            if ($activationCode->status === 'REDEEMED' || $activationCode->is_used) {
                abort(409, 'Activation code has already been used.');
            }

            if ($activationCode->status === 'VOIDED') {
                abort(409, 'Activation code has been voided.');
            }

            if ($activationCode->expires_at && $activationCode->expires_at->isPast()) {
                abort(400, 'Activation code has expired.');
            }

            // Mark as used
            $activationCode->update([
                'status' => 'REDEEMED',
                'is_used' => true,
                'used_by_user_id' => $user->id,
                'used_at' => now(),
            ]);

            $plan = $activationCode->plan;

            // Mark existing active subscriptions as expired if we are activating a new one
            Subscription::where('user_id', $user->id)
                ->where('status', SubscriptionStatus::ACTIVE)
                ->update([
                    'status' => SubscriptionStatus::EXPIRED,
                    'active_flag' => null,
                ]);

            // Create new subscription
            $durationDays = $plan->duration_days ?? 30; // default 30 days if not set

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => SubscriptionStatus::ACTIVE,
                'active_flag' => 1,
                'started_at' => now(),
                'expires_at' => now()->addDays($durationDays),
                'remaining_minutes' => $plan->included_minutes,
            ]);

            $activationCode->update(['redeemed_subscription_id' => $subscription->id]);

            return $subscription->load('plan');
        });
    }
}
