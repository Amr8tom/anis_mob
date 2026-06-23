<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class WorkspaceWalkIn extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saving(function (self $walkIn): void {
            if ($walkIn->isDirty('phone_number')) {
                $normalized = preg_replace('/\D+/', '', (string) $walkIn->phone_number) ?? '';
                $walkIn->phone_number_normalized = $normalized === '' ? null : $normalized;
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(WorkspaceVisit::class, 'walk_in_id');
    }
}
