<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Actions;

use App\Models\Plan;
use App\Models\PlanActivationCode;
use Illuminate\Support\Str;

final readonly class GeneratePlanCodeAction
{
    public function handle(Plan $plan, ?string $adminId = null, ?\DateTimeInterface $expiresAt = null, int $count = 1): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            // Generate a readable random code, e.g. GOLD-XXXX-XXXX
            $randomPart = strtoupper(Str::random(4)).'-'.strtoupper(Str::random(4));
            $prefix = strtoupper(substr($plan->tier->value ?? 'PLAN', 0, 4));
            $codeStr = "{$prefix}-{$randomPart}";

            $codes[] = PlanActivationCode::create([
                'plan_id' => $plan->id,
                'code' => $codeStr,
                'status' => 'ACTIVE',
                'is_used' => false,
                'expires_at' => $expiresAt,
                'created_by_admin_id' => $adminId,
            ]);
        }

        return $codes;
    }
}
