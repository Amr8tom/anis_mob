<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkspacePrivateSessionAttendeeFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WorkspacePrivateSessionAttendee extends Model
{
    /** @use HasFactory<WorkspacePrivateSessionAttendeeFactory> */
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'amount_cents' => 'integer',
        ];
    }

    /** @return BelongsTo<WorkspacePrivateSession, $this> */
    public function privateSession(): BelongsTo
    {
        return $this->belongsTo(WorkspacePrivateSession::class, 'workspace_private_session_id');
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

    public function amountEgp(): float
    {
        return $this->amount_cents / 100;
    }
}
