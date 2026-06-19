<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoomReservationStatus;
use Database\Factories\RoomReservationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomReservation extends Model
{
    /** @use HasFactory<RoomReservationFactory> */
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => RoomReservationStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'hourly_price_cents' => 'integer',
            'total_cost_cents' => 'integer',
        ];
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return BelongsTo<WorkspaceRoom, $this> */
    public function room(): BelongsTo
    {
        return $this->belongsTo(WorkspaceRoom::class, 'room_id');
    }

    /** @return BelongsTo<RoomClient, $this> */
    public function client(): BelongsTo
    {
        return $this->belongsTo(RoomClient::class, 'room_client_id');
    }

    public function durationMinutes(): int
    {
        return (int) abs($this->starts_at->diffInMinutes($this->ends_at));
    }

    public function totalCostEgp(): float
    {
        return round($this->total_cost_cents / 100, 2);
    }
}
