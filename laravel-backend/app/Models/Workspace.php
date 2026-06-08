<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WorkspaceStatus;
use Database\Factories\WorkspaceFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    /** @use HasFactory<WorkspaceFactory> */
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'gallery_images' => 'array',
            'amenities' => 'array',
            'status' => WorkspaceStatus::class,
            'capacity' => 'integer',
            'manual_occupancy' => 'integer',
            'day_calculation_hours' => 'integer',
            'hour_multiplier' => 'float',
            'is_active' => 'boolean',
        ];
    }

    protected $appends = ['is_full', 'computed_occupancy'];

    /**
     * Computed attribute: The effective occupancy. 
     * Prioritizes manual override, falls back to counted visits.
     */
    public function getComputedOccupancyAttribute(): int
    {
        if ($this->manual_occupancy !== null) {
            return $this->manual_occupancy;
        }

        // Falls back to the relation count if loaded, else 0
        return (int) ($this->attributes['current_occupancy'] ?? 0);
    }

    /**
     * Computed attribute: Is the workspace full?
     */
    public function getIsFullAttribute(): bool
    {
        if ($this->status === WorkspaceStatus::FULL) {
            return true;
        }

        if (!$this->capacity) {
            return false;
        }

        return $this->computed_occupancy >= $this->capacity;
    }

    /** @return HasMany<StudySession, $this> */
    public function sessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    /** @return HasMany<WorkspaceVisit, $this> */
    public function visits(): HasMany
    {
        return $this->hasMany(WorkspaceVisit::class);
    }

    /** @return HasMany<WorkspaceDrink, $this> */
    public function drinks(): HasMany
    {
        return $this->hasMany(WorkspaceDrink::class);
    }

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
