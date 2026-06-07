<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Maps a Workspace to the Flutter WorkspaceModel (camelCase, lowercase status).
 *
 * @mixin Workspace
 */
final class WorkspaceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'description' => $this->description ?? '',
            'latitude' => (float) ($this->latitude ?? 0),
            'longitude' => (float) ($this->longitude ?? 0),
            'galleryImages' => $this->gallery_images ?? [],
            'drinks' => WorkspaceDrinkResource::collection($this->whenLoaded('drinks')),
            'currentOccupancy' => (int) ($this->current_occupancy ?? 0),
            'capacity' => (int) ($this->capacity ?? 0),
            'status' => strtolower($this->status->value),
            'distanceKm' => round((float) ($this->distance_km ?? 0), 2),
            'openTime' => $this->open_time ?? '',
            'closeTime' => $this->close_time ?? '',
            'dayCalculationHours' => (int) $this->day_calculation_hours,
            'hourMultiplier' => (float) ($this->hour_multiplier ?? 1.00),
            'amenities' => $this->amenities ?? [],
            'sessions' => SessionCardResource::collection($this->whenLoaded('sessions')),
        ];
    }
}
