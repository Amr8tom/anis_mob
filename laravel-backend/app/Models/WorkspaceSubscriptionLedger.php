<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\WorkspaceLedgerReason;
use App\Models\Concerns\AppendOnly;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceSubscriptionLedger extends Model
{
    use AppendOnly, HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    // Append-only audit rows: created_at only, never updated.
    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'reason' => WorkspaceLedgerReason::class,
            'change_minutes' => 'integer',
            'balance_after' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WorkspaceSubscription, $this> */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(WorkspaceSubscription::class, 'workspace_subscription_id');
    }

    /** @return BelongsTo<WorkspaceVisit, $this> */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(WorkspaceVisit::class, 'workspace_visit_id');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
