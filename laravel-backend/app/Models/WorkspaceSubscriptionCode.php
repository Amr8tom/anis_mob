<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WorkspaceSubscriptionCodeStatus;
use Database\Factories\WorkspaceSubscriptionCodeFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceSubscriptionCode extends Model
{
    /** @use HasFactory<WorkspaceSubscriptionCodeFactory> */
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => WorkspaceSubscriptionCodeStatus::class,
            'expires_at' => 'datetime',
            'redeemed_at' => 'datetime',
            'revoked_at' => 'datetime',
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
    public function redeemedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by_user_id');
    }

    public function isRedeemable(): bool
    {
        if ($this->status !== WorkspaceSubscriptionCodeStatus::UNUSED) {
            return false;
        }

        return $this->expires_at === null || $this->expires_at->isFuture();
    }
}
