<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class NotificationCampaign extends Model
{
    use HasUuids;

    public const STATUS_PENDING = 'pending';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_BUILDING = 'building';
    public const STATUS_SENDING = 'sending';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    public const CATEGORY_SESSION_REMINDERS = UserNotificationPreference::CATEGORY_SESSION_REMINDERS;
    public const CATEGORY_SUBSCRIPTION_ALERTS = UserNotificationPreference::CATEGORY_SUBSCRIPTION_ALERTS;
    public const CATEGORY_OFFERS_MARKETING = UserNotificationPreference::CATEGORY_OFFERS_MARKETING;
    public const CATEGORY_WORKSPACE_UPDATES = UserNotificationPreference::CATEGORY_WORKSPACE_UPDATES;

    protected $guarded = ['id'];

    protected $casts = [
        'target_payload' => 'array',
        'targeted_count' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
        'skipped_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
        'queued_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'recipients_pruned_at' => 'datetime',
    ];

    /** @return BelongsTo<Workspace, NotificationCampaign> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return HasMany<NotificationRecipient, NotificationCampaign> */
    public function recipients(): HasMany
    {
        return $this->hasMany(NotificationRecipient::class, 'campaign_id');
    }
}
