<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkspaceClientFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WorkspaceClient extends Model
{
    /** @use HasFactory<WorkspaceClientFactory> */
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'first_visit_at' => 'datetime',
            'last_visit_at' => 'datetime',
            'total_visits' => 'integer',
            'total_minutes' => 'integer',
            'free_visits' => 'integer',
            'global_subscription_visits' => 'integer',
            'workspace_subscription_visits' => 'integer',
        ];
    }

    public function getFullNameAttribute(): ?string
    {
        return $this->full_name_snapshot;
    }

    public function getPhoneNumberAttribute(): ?string
    {
        return $this->phone_number_snapshot;
    }

    public function getLastVisitAttribute(): mixed
    {
        return $this->last_visit_at;
    }

    public function getSourceIdAttribute(): ?string
    {
        return $this->user_id ?? $this->walk_in_id;
    }

    public function getClientRouteTypeAttribute(): string
    {
        return $this->client_type === 'USER' ? 'user' : 'walk_in';
    }

    public function getPlanTiersAttribute(): string
    {
        $tiers = [];
        if ($this->free_visits > 0) {
            $tiers[] = 'FREE';
        }
        if ($this->global_subscription_visits > 0) {
            $tiers[] = 'GLOBAL_SUBSCRIPTION';
        }
        if ($this->workspace_subscription_visits > 0) {
            $tiers[] = 'WORKSPACE_SUBSCRIPTION';
        }

        return implode(',', $tiers === [] ? ['FREE'] : $tiers);
    }

    /** @return BelongsTo<Workspace, $this> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
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
}
