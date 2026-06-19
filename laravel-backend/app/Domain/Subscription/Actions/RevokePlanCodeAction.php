<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Actions;

use App\Models\PlanActivationCode;

final readonly class RevokePlanCodeAction
{
    public function handle(PlanActivationCode $activationCode, ?string $adminId = null): PlanActivationCode
    {
        if ($activationCode->status === 'REDEEMED' || $activationCode->is_used) {
            abort(400, 'Cannot revoke an already redeemed code.');
        }

        if ($activationCode->status === 'VOIDED') {
            return $activationCode;
        }

        $activationCode->update([
            'status' => 'VOIDED',
            'voided_by_admin_id' => $adminId,
            'voided_at' => now(),
        ]);

        return $activationCode;
    }
}
