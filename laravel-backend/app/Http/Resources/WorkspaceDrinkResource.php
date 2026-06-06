<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\WorkspaceDrink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WorkspaceDrink
 */
final class WorkspaceDrinkResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'price' => round($this->price_cents / 100, 2), // cents -> currency units
        ];
    }
}
