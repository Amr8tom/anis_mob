<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\AppendOnly;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class WorkspaceOwnershipChange extends Model
{
    use AppendOnly, HasUuids;

    public const UPDATED_AT = null;

    protected $guarded = ['id'];
}
