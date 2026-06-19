<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSettlement\Actions;

use App\Models\AccountingCorrection;
use App\Models\Subscription;
use App\Models\SubscriptionLedger;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class CreateAccountingCorrectionAction
{
    public function handle(WorkspaceVisit $visit, string $adminId, int $minutesDelta, string $reason): AccountingCorrection
    {
        return DB::transaction(function () use ($visit, $adminId, $minutesDelta, $reason): AccountingCorrection {
            $subscription = $visit->subscription_id === null
                ? null
                : Subscription::whereKey($visit->subscription_id)->lockForUpdate()->first();

            if ($subscription?->remaining_minutes !== null) {
                $after = $subscription->remaining_minutes + $minutesDelta;
                if ($after < 0) {
                    throw ValidationException::withMessages(['minutes_delta' => 'Correction would make the subscription balance negative.']);
                }
                $subscription->update(['remaining_minutes' => $after]);
                SubscriptionLedger::create([
                    'subscription_id' => $subscription->id,
                    'visit_id' => $visit->id,
                    'user_id' => $visit->user_id,
                    'change_minutes' => $minutesDelta,
                    'balance_after' => $after,
                    'reason' => 'ADMIN_CORRECTION',
                ]);
            }

            return AccountingCorrection::create([
                'workspace_visit_id' => $visit->id,
                'subscription_id' => $subscription?->id,
                'created_by_admin_id' => $adminId,
                'minutes_delta' => $minutesDelta,
                'reason' => $reason,
            ]);
        });
    }
}
