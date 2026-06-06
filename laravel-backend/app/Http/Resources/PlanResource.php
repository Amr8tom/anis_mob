<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Plan
 */
final class PlanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tier' => strtolower($this->tier->value),
            'price' => round($this->price_cents / 100, 2),
            'currency' => $this->currency,
            'includedMinutes' => $this->included_minutes,
            'durationDays' => $this->duration_days,
            // 'time-balance' plans use includedMinutes; 'date-bound' plans use durationDays.
            'billingType' => $this->included_minutes !== null ? 'timeBalance' : 'dateBound',
        ];
    }
}
