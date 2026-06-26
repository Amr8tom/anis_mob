<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ScheduledNotificationTask extends Model
{
    use HasUuids;

    public const STATUS_PENDING = 'pending';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_FAILED = 'failed';

    public const TYPE_PUBLIC_SESSION_18H = 'public_session_18h_reminder';
    public const TYPE_PRIVATE_SESSION_18H = 'private_session_18h_reminder';
    public const TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D = 'workspace_subscription_expiry_2d';
    public const TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H = 'workspace_subscription_low_hours_16h';

    protected $guarded = ['id'];

    protected $casts = [
        'due_at' => 'datetime',
        'payload' => 'array',
        'processed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /** @return BelongsTo<Workspace, ScheduledNotificationTask> */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /** @return BelongsTo<NotificationCampaign, ScheduledNotificationTask> */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(NotificationCampaign::class);
    }
}
