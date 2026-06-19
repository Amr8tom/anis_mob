<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionCodeRepositoryInterface;
use App\Domain\WorkspaceSubscription\Support\WorkspaceSubscriptionActivator;
use App\Enums\WorkspaceSubscriptionCodeStatus;
use App\Enums\WorkspaceSubscriptionDelivery;
use App\Exceptions\InvalidWorkspaceCodeException;
use App\Exceptions\WorkspaceCodeAlreadyUsedException;
use App\Models\WorkspaceSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class RedeemWorkspaceSubscriptionCodeAction
{
    public function __construct(
        private WorkspaceSubscriptionCodeRepositoryInterface $codes,
        private WorkspacePlanRepositoryInterface $plans,
        private WorkspaceSubscriptionActivator $activator,
    ) {}

    public function handle(string $rawCode, string $userId): WorkspaceSubscription
    {
        $code = strtoupper(trim($rawCode));

        return DB::transaction(function () use ($code, $userId): WorkspaceSubscription {
            $codeRow = $this->codes->lockByCode($code);

            if ($codeRow === null) {
                throw new InvalidWorkspaceCodeException;
            }
            if ($codeRow->status !== WorkspaceSubscriptionCodeStatus::UNUSED) {
                throw new WorkspaceCodeAlreadyUsedException;
            }
            if ($codeRow->expires_at !== null && $codeRow->expires_at->isPast()) {
                $codeRow->update(['status' => WorkspaceSubscriptionCodeStatus::EXPIRED->value]);
                throw new InvalidWorkspaceCodeException('This code has expired.');
            }

            $plan = $this->plans->findForWorkspace($codeRow->workspace_plan_id, $codeRow->workspace_id);
            if ($plan === null || ! $plan->is_active) {
                throw new InvalidWorkspaceCodeException('The plan for this code is no longer available.');
            }

            // Throws AlreadyHasWorkspaceSubscriptionException on the R9 guard.
            $subscription = $this->activator->activate(
                $plan,
                $userId,
                WorkspaceSubscriptionDelivery::ACTIVATION_CODE,
                $codeRow->created_by_owner_id,
            );

            $codeRow->update([
                'status' => WorkspaceSubscriptionCodeStatus::REDEEMED->value,
                'redeemed_by_user_id' => $userId,
                'redeemed_at' => now(),
                'workspace_subscription_id' => $subscription->id,
            ]);

            Log::info('workspace_subscription.code_redeemed', [
                'code_id' => $codeRow->id,
                'subscription_id' => $subscription->id,
                'user_id' => $userId,
            ]);

            return $subscription->load('workspace');
        });
    }
}
