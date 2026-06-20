<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkspacePrivateSessionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class WorkspacePrivateSession extends Model
{
    /** @use HasFactory<WorkspacePrivateSessionFactory> */
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'capacity' => 'integer',
            'price_cents' => 'integer',
        ];
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_owner_id');
    }

    /** @return HasMany<WorkspacePrivateSessionAttendee, $this> */
    public function attendees(): HasMany
    {
        return $this->hasMany(WorkspacePrivateSessionAttendee::class);
    }

    public function priceEgp(): float
    {
        return $this->price_cents / 100;
    }

    /**
     * @return array{
     *     invited:int,
     *     attended:int,
     *     not_attended:int,
     *     expected_revenue_cents:int,
     *     actual_revenue_cents:int,
     *     qr_checkins:int,
     *     owner_checkins:int
     * }
     */
    public function summary(): array
    {
        $attendees = $this->relationLoaded('attendees')
            ? $this->attendees
            : $this->attendees()->get();

        $invited = $attendees->count();
        $attended = $attendees->where('status', 'attended');

        return [
            'invited' => $invited,
            'attended' => $attended->count(),
            'not_attended' => max(0, $invited - $attended->count()),
            'expected_revenue_cents' => $invited * (int) $this->price_cents,
            'actual_revenue_cents' => (int) $attended->sum('amount_cents'),
            'qr_checkins' => $attended->where('checked_in_method', 'qr')->count(),
            'owner_checkins' => $attended->where('checked_in_method', 'owner')->count(),
        ];
    }
}
