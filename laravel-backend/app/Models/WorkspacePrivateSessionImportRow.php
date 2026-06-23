<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WorkspacePrivateSessionImportRow extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    /** @return BelongsTo<WorkspacePrivateSessionImportBatch, $this> */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(WorkspacePrivateSessionImportBatch::class, 'workspace_private_session_import_batch_id');
    }
}
