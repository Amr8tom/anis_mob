<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class NotificationRecipient extends Model
{
    use HasUuids;

    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SKIPPED = 'skipped';

    protected $guarded = ['id'];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'open_count' => 'integer',
        'click_count' => 'integer',
    ];

    /** @return BelongsTo<NotificationCampaign, NotificationRecipient> */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(NotificationCampaign::class, 'campaign_id');
    }

    /** @return BelongsTo<User, NotificationRecipient> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<DeviceToken, NotificationRecipient> */
    public function deviceToken(): BelongsTo
    {
        return $this->belongsTo(DeviceToken::class);
    }
}
