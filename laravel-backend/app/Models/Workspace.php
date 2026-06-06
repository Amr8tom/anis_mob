<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WorkspaceStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    /** @use HasFactory<\Database\Factories\WorkspaceFactory> */
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
            'day_calculation_hours' => 'integer',
            'is_active' => 'boolean',
        ];
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
}
