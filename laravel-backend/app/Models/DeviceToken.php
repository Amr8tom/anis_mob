<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DeviceToken extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'token',
        'platform',
        'is_active',
        'failure_count',
        'last_used_at',
        'last_failed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'failure_count' => 'integer',
        'last_used_at' => 'datetime',
        'last_failed_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, DeviceToken>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
