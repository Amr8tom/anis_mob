<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UserNotificationPreference extends Model
{
    public const CATEGORY_SESSION_REMINDERS = 'session_reminders';
    public const CATEGORY_SUBSCRIPTION_ALERTS = 'subscription_alerts';
    public const CATEGORY_OFFERS_MARKETING = 'offers_marketing';
    public const CATEGORY_WORKSPACE_UPDATES = 'workspace_updates';

    public const CATEGORIES = [
        self::CATEGORY_SESSION_REMINDERS,
        self::CATEGORY_SUBSCRIPTION_ALERTS,
        self::CATEGORY_OFFERS_MARKETING,
        self::CATEGORY_WORKSPACE_UPDATES,
    ];

    public const DEFAULTS = [
        'session_reminders' => true,
        'subscription_alerts' => true,
        'offers_marketing' => true,
        'workspace_updates' => true,
    ];

    protected $primaryKey = 'user_id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'session_reminders',
        'subscription_alerts',
        'offers_marketing',
        'workspace_updates',
    ];

    protected $casts = [
        'session_reminders' => 'boolean',
        'subscription_alerts' => 'boolean',
        'offers_marketing' => 'boolean',
        'workspace_updates' => 'boolean',
    ];

    public static function normalizeCategory(?string $category): string
    {
        return in_array($category, self::CATEGORIES, true)
            ? $category
            : self::CATEGORY_WORKSPACE_UPDATES;
    }

    /**
     * @return BelongsTo<User, UserNotificationPreference>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
