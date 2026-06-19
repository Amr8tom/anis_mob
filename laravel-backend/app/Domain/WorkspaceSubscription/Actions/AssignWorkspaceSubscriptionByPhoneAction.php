<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Support\WorkspaceSubscriptionActivator;
use App\Enums\WorkspaceSubscriptionDelivery;
use App\Exceptions\PhoneNotRegisteredException;
use App\Models\User;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

final readonly class AssignWorkspaceSubscriptionByPhoneAction
{
    public function __construct(private WorkspaceSubscriptionActivator $activator) {}

    public function handle(WorkspacePlan $plan, string $phoneNumber, string $ownerId): WorkspaceSubscription
    {
        if (! $plan->is_active) {
            throw ValidationException::withMessages(['workspace_plan_id' => 'لا يمكن إصدار اشتراك من باقة موقوفة.']);
        }

        return DB::transaction(function () use ($plan, $phoneNumber, $ownerId): WorkspaceSubscription {
            // R10: direct assignment only for a registered app user.
            $user = User::query()->where('phone_number', trim($phoneNumber))->first();
            if ($user === null) {
                throw new PhoneNotRegisteredException;
            }

            $subscription = $this->activator->activate(
                $plan,
                $user->id,
                WorkspaceSubscriptionDelivery::DIRECT_ASSIGNMENT,
                $ownerId,
            );

            Log::info('workspace_subscription.assigned_by_phone', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'owner_id' => $ownerId,
            ]);

            return $subscription->load('workspace');
        });
    }
}
