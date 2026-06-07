<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VisitStatus;
use Database\Factories\WorkspaceVisitFactory;
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

    protected function casts(): array
    {
        return [
            'status' => VisitStatus::class,
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
            'duration_minutes' => 'integer',
            'billable_minutes' => 'integer',
            'deducted_minutes' => 'integer',
            'hour_multiplier_applied' => 'float',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
