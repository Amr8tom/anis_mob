<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkspaceOwnerFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A workspace/center owner — authenticates through the `workspace_owner` guard.
 * Kept entirely separate from App\Models\User (regular app users).
 */
class WorkspaceOwner extends Authenticatable
{
    /** @use HasFactory<WorkspaceOwnerFactory> */
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'full_name',
        'phone_number',
        'phone_number_normalized',
        'email',
        'password',
        'status',
        'last_login_at',
        'locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $owner): void {
            if ($owner->isDirty('phone_number')) {
                $owner->phone_number_normalized = self::normalizePhone($owner->phone_number);
            }
        });
    }

    public static function normalizePhone(?string $phone): ?string
    {
        $normalized = preg_replace('/\D+/', '', (string) $phone) ?? '';

        return $normalized === '' ? null : $normalized;
    }

    /** @return HasOne<Workspace, $this> */
    public function workspace(): HasOne
    {
        return $this->hasOne(Workspace::class, 'workspace_owner_id');
    }

    /** @return HasOne<Workspace, $this> */
    public function ownedWorkspace(): HasOne
    {
        return $this->workspace();
    }
}
