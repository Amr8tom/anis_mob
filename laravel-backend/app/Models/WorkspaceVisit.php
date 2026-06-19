<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BillingSource;
use App\Enums\PlanTier;
use App\Enums\VisitStatus;
use Database\Factories\WorkspaceVisitFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceVisit extends Model
{
    /** @use HasFactory<WorkspaceVisitFactory> */
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::creating(function (self $visit): void {
            if ($visit->billing_source !== null) {
                return;
            }

            $visit->billing_source = match (true) {
                $visit->workspace_subscription_id !== null => BillingSource::WORKSPACE_SUBSCRIPTION,
                $visit->subscription_id !== null => BillingSource::GLOBAL_SUBSCRIPTION,
                default => BillingSource::FREE,
            };
        });

        static::deleting(fn () => throw new \LogicException('Attendance history cannot be deleted.'));
        static::updating(function (self $visit): void {
            $dirty = array_keys($visit->getDirty());

            // Allowed mutation #1: a still-open visit records/clears a checkout
            // request. Status stays CHECKED_IN; no billing fields touched.
            $requestColumns = ['checkout_requested_at', 'checkout_request_note', 'updated_at'];
            if (
                $visit->getRawOriginal('status') === VisitStatus::CHECKED_IN->value
                && $visit->status === VisitStatus::CHECKED_IN
                && array_diff($dirty, $requestColumns) === []
            ) {
                return;
            }

            // Allowed mutation #2: the one-way CHECKED_IN -> CHECKED_OUT close.
            $allowed = ['status', 'check_out_at', 'duration_minutes', 'billable_minutes', 'deducted_minutes', 'hour_multiplier_applied', 'active_flag', 'checkout_requested_at', 'checkout_request_note', 'updated_at'];
            if (
                $visit->getRawOriginal('status') !== VisitStatus::CHECKED_IN->value
                || $visit->status !== VisitStatus::CHECKED_OUT
                || array_diff($dirty, $allowed) !== []
            ) {
                throw new \LogicException('Completed attendance history is immutable; create a correction instead.');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'status' => VisitStatus::class,
            'plan_tier_snapshot' => PlanTier::class,
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'duration_minutes' => 'integer',
            'billable_minutes' => 'integer',
            'deducted_minutes' => 'integer',
            'hour_multiplier_applied' => 'float',
            'checkout_requested_at' => 'datetime',
            'workspace_client_counted_at' => 'datetime',
            'billing_source' => BillingSource::class,
        ];
    }

    /** @return BelongsTo<WorkspaceSubscription, $this> */
    public function workspaceSubscription(): BelongsTo
    {
        return $this->belongsTo(WorkspaceSubscription::class);
    }

    /**
     * A still-open visit whose holder has asked the owner to check them out.
     */
    public function hasPendingCheckoutRequest(): bool
    {
        return $this->status === VisitStatus::CHECKED_IN && $this->checkout_requested_at !== null;
    }

    /**
     * @param  Builder<WorkspaceVisit>  $query
     * @return Builder<WorkspaceVisit>
     */
    public function scopePendingCheckoutRequests(Builder $query): Builder
    {
        return $query
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->whereNotNull('checkout_requested_at');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function walkIn(): BelongsTo
    {
        return $this->belongsTo(WorkspaceWalkIn::class, 'walk_in_id');
    }

    public function getVisitorNameAttribute(): string
    {
        return $this->user?->full_name ?? $this->walkIn?->full_name ?? 'Unknown visitor';
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return BelongsTo<Subscription, $this> */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
