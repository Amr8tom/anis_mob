<?php

namespace App\Models;

use App\Models\Concerns\AppendOnly;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceSettlement extends Model
{
    use AppendOnly, HasFactory, HasUuids;

    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $casts = [
        'period_started_at' => 'datetime',
        'period_ended_at' => 'datetime',
        'paid_at' => 'datetime',
        'total_visits' => 'integer',
        'unique_visitors' => 'integer',
        'total_minutes' => 'integer',
        'free_visits' => 'integer',
        'free_visitors' => 'integer',
        'free_minutes' => 'integer',
        'global_subscription_visits' => 'integer',
        'global_subscription_visitors' => 'integer',
        'global_subscription_minutes' => 'integer',
        'workspace_subscription_visits' => 'integer',
        'workspace_subscription_visitors' => 'integer',
        'workspace_subscription_minutes' => 'integer',
        'amount_cents' => 'integer',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function reversal()
    {
        return $this->hasOne(WorkspaceSettlementReversal::class, 'workspace_settlement_id');
    }
}
