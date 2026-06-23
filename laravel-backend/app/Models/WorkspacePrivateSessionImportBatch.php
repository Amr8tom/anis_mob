<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class WorkspacePrivateSessionImportBatch extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'valid_count' => 'integer',
            'duplicate_count' => 'integer',
            'failed_count' => 'integer',
            'confirmed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WorkspacePrivateSession, $this> */
    public function privateSession(): BelongsTo
    {
        return $this->belongsTo(WorkspacePrivateSession::class, 'workspace_private_session_id');
    }

    /** @return HasMany<WorkspacePrivateSessionImportRow, $this> */
    public function rows(): HasMany
    {
        return $this->hasMany(WorkspacePrivateSessionImportRow::class, 'workspace_private_session_import_batch_id');
    }
}
