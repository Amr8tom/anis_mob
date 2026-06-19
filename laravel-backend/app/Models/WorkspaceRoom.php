<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkspaceRoomFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkspaceRoom extends Model
{
    /** @use HasFactory<WorkspaceRoomFactory> */
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'hourly_price_cents' => 'integer',
            'position' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return HasMany<RoomReservation, $this> */
    public function reservations(): HasMany
    {
        return $this->hasMany(RoomReservation::class, 'room_id');
    }

    public function hourlyPriceEgp(): float
    {
        return round($this->hourly_price_cents / 100, 2);
    }
}
