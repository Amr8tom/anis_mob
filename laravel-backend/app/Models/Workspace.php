<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CheckoutMode;
use App\Enums\WorkspaceLifecycleStatus;
use App\Enums\WorkspaceStatus;
use Database\Factories\WorkspaceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    /** @use HasFactory<WorkspaceFactory> */
    use HasFactory, HasUuids, SoftDeletes;

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
            'lifecycle_status' => WorkspaceLifecycleStatus::class,
            'approved_at' => 'datetime',
            'suspended_at' => 'datetime',
            'checkout_mode' => CheckoutMode::class,
            'capacity' => 'integer',
            'manual_occupancy' => 'integer',
            'active_visit_count' => 'integer',
            'recent_visits_cleared_at' => 'datetime',
            'day_calculation_hours' => 'integer',
            'hour_multiplier' => 'float',
            'payout_rate_cents_per_hour' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected $appends = ['is_full', 'computed_occupancy', 'cover_image_url', 'gallery_urls'];

    // ---- Lifecycle (admin-control axis) ----

    public function isApproved(): bool
    {
        return $this->lifecycle_status === WorkspaceLifecycleStatus::APPROVED;
    }

    public function isPending(): bool
    {
        return $this->lifecycle_status === WorkspaceLifecycleStatus::PENDING;
    }

    public function isSuspended(): bool
    {
        return $this->lifecycle_status === WorkspaceLifecycleStatus::SUSPENDED;
    }

    /**
     * Approved AND switched on — the single definition of "visible to visitors".
     *
     * @param  Builder<Workspace>  $query
     */
    public function scopeVisibleToVisitors(Builder $query): void
    {
        $query
            ->where('lifecycle_status', WorkspaceLifecycleStatus::APPROVED->value)
            ->where('is_active', true);
    }

    /**
     * Computed attribute: Full URL for the cover image.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        $path = $this->attributes['cover_image_url'] ?? null;
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return $this->mediaUrl($path);
    }

    /**
     * Computed attribute: Full URLs for gallery images.
     */
    public function getGalleryUrlsAttribute(): array
    {
        $value = $this->attributes['gallery_images'] ?? null;
        if (! $value) {
            return [];
        }

        $images = is_string($value) ? json_decode($value, true) : $value;
        if (! is_array($images)) {
            return [];
        }

        return array_map(function ($path) {
            if (str_starts_with($path, 'http')) {
                return $path;
            }

            return $this->mediaUrl($path);
        }, $images);
    }

    private function mediaUrl(string $path): string
    {
        // Already an absolute URL (e.g. external/CDN image) — use as-is.
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Normalise a legacy "/storage/workspaces/x.jpg" value back to a disk path.
        $diskPath = ltrim(preg_replace('#^/?storage/#', '', $path) ?? $path, '/');

        // Route the file through the media endpoint, which reads directly from the
        // disk. This avoids the public/storage symlink dependency and always resolves
        // to the current request host (so it works regardless of APP_URL).
        // Built with url() (not route()) so path slashes are not encoded to %2F.
        return url('media/' . $diskPath);
    }

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

        if (! $this->capacity) {
            return false;
        }

        return $this->computed_occupancy >= $this->capacity;
    }

    /**
     * Whether paid visitors must request checkout for owner approval.
     */
    public function requiresCheckoutApproval(): bool
    {
        return ($this->checkout_mode ?? CheckoutMode::DIRECT) === CheckoutMode::APPROVAL;
    }

    public function baseHourlyRateEgp(): float
    {
        return round((int) ($this->payout_rate_cents_per_hour ?? 1500) / 100, 2);
    }

    public function effectiveHourlyRateEgp(): float
    {
        return round($this->baseHourlyRateEgp() * (float) ($this->hour_multiplier ?? 1.0), 2);
    }

    /**
     * Maximum billable minutes for a single visit/day: a visitor is never charged
     * for more than the workspace's configured "ساعات احتساب اليوم".
     */
    public function dailyCapMinutes(): int
    {
        return (int) (($this->day_calculation_hours ?? 8) * 60);
    }

    /**
     * Estimated revenue for a visit, capped at the daily-hours ceiling so a long
     * stay can never bill above (day_calculation_hours × hourly rate).
     */
    public function estimatedRevenueEgp(int $minutes): float
    {
        $capped = min(max($minutes, 0), $this->dailyCapMinutes());

        return round(($capped / 60) * $this->effectiveHourlyRateEgp(), 2);
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

    /** @return HasMany<WorkspacePlan, $this> */
    public function workspacePlans(): HasMany
    {
        return $this->hasMany(WorkspacePlan::class);
    }

    /** @return HasMany<WorkspaceSubscription, $this> */
    public function workspaceSubscriptions(): HasMany
    {
        return $this->hasMany(WorkspaceSubscription::class);
    }

    /** @return HasMany<WorkspaceRoom, $this> */
    public function rooms(): HasMany
    {
        return $this->hasMany(WorkspaceRoom::class);
    }

    /** @return HasMany<RoomReservation, $this> */
    public function roomReservations(): HasMany
    {
        return $this->hasMany(RoomReservation::class);
    }

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /** @return HasMany<WorkspaceSettlement, $this> */
    public function settlements(): HasMany
    {
        return $this->hasMany(WorkspaceSettlement::class);
    }
}
