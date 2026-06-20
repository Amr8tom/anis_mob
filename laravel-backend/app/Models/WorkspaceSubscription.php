<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WorkspaceSubscriptionDelivery;
use App\Enums\WorkspaceSubscriptionStatus;
use Database\Factories\WorkspaceSubscriptionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkspaceSubscription extends Model
{
    /** @use HasFactory<WorkspaceSubscriptionFactory> */
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => WorkspaceSubscriptionStatus::class,
            'delivery_method' => WorkspaceSubscriptionDelivery::class,
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'remaining_minutes' => 'integer',
            'total_minutes' => 'integer',
            'duration_days_snapshot' => 'integer',
            'price_cents_snapshot' => 'integer',
            'active_flag' => 'integer',
        ];
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return BelongsTo<WorkspacePlan, $this> */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(WorkspacePlan::class, 'workspace_plan_id');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<WorkspaceWalkIn, $this> */
    public function walkIn(): BelongsTo
    {
        return $this->belongsTo(WorkspaceWalkIn::class, 'walk_in_id');
    }

    /** Display name of the subscriber, whether an app user or a walk-in. */
    public function subscriberName(): string
    {
        return $this->user?->full_name ?? $this->walkIn?->full_name ?? 'زائر';
    }

    /** Phone of the subscriber, whether an app user or a walk-in. */
    public function subscriberPhone(): ?string
    {
        return $this->user?->phone_number ?? $this->walkIn?->phone_number;
    }

    /** @return HasMany<WorkspaceSubscriptionLedger, $this> */
    public function ledgers(): HasMany
    {
        return $this->hasMany(WorkspaceSubscriptionLedger::class);
    }

    /**
     * Usable = ACTIVE, not past expiry, and has minutes left.
     */
    public function isUsable(): bool
    {
        if ($this->status !== WorkspaceSubscriptionStatus::ACTIVE) {
            return false;
        }
        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        return $this->remaining_minutes > 0;
    }

    public function daysLeft(): int
    {
        if ($this->expires_at === null) {
            return 0;
        }

        return max(0, (int) ceil(now()->diffInDays($this->expires_at, false)));
    }
}
