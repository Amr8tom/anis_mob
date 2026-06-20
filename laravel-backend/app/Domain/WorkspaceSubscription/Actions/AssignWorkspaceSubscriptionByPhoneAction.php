<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Support\WorkspaceSubscriptionActivator;
use App\Enums\WorkspaceSubscriptionDelivery;
use App\Models\User;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceWalkIn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

final readonly class AssignWorkspaceSubscriptionByPhoneAction
{
    public function __construct(private WorkspaceSubscriptionActivator $activator) {}

    /**
     * Assign a special (workspace) plan by phone. If the phone belongs to a
     * registered app user, the subscription is theirs. Otherwise the visitor is
     * registered as a walk-in (created if new) and the plan is assigned to them.
     */
    public function handle(WorkspacePlan $plan, string $phoneNumber, string $ownerId, ?string $name = null): WorkspaceSubscription
    {
        if (! $plan->is_active) {
            throw ValidationException::withMessages(['workspace_plan_id' => 'لا يمكن إصدار اشتراك من باقة موقوفة.']);
        }

        $phone = trim($phoneNumber);

        return DB::transaction(function () use ($plan, $phone, $ownerId, $name): WorkspaceSubscription {
            $user = User::query()->where('phone_number', $phone)->first();

            if ($user !== null) {
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
            }

            // Not an app user — register as a walk-in for this workspace, then assign.
            $walkIn = WorkspaceWalkIn::withTrashed()->firstOrCreate(
                ['workspace_id' => $plan->workspace_id, 'phone_number' => $phone],
                ['full_name' => filled($name) ? trim((string) $name) : 'زائر'],
            );
            if ($walkIn->trashed()) {
                $walkIn->restore();
            }
            if (filled($name) && $walkIn->full_name !== trim((string) $name)) {
                $walkIn->update(['full_name' => trim((string) $name)]);
            }

            $subscription = $this->activator->activateForWalkIn(
                $plan,
                $walkIn->id,
                WorkspaceSubscriptionDelivery::DIRECT_ASSIGNMENT,
                $ownerId,
            );

            Log::info('workspace_subscription.assigned_by_phone_walkin', [
                'subscription_id' => $subscription->id,
                'walk_in_id' => $walkIn->id,
                'owner_id' => $ownerId,
            ]);

            return $subscription->load('workspace');
        });
    }
}
