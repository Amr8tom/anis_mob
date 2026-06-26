<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Actions;

use App\Domain\Notifications\Jobs\DispatchNotificationCampaignJob;
use App\Domain\Notifications\Services\NotificationSendWindowService;
use App\Models\NotificationCampaign;
use App\Models\UserNotificationPreference;

final readonly class CreateNotificationCampaignAction
{
    public function __construct(private NotificationSendWindowService $sendWindow)
    {
    }

    public function execute(array $attributes): NotificationCampaign
    {
        $scheduledAt = $this->sendWindow->resolveScheduledAt($attributes['scheduled_at'] ?? null);
        $isDueNow = $scheduledAt->lessThanOrEqualTo(now());

        $campaign = NotificationCampaign::create([
            'workspace_id' => $attributes['workspace_id'] ?? null,
            'sender_type' => $attributes['sender_type'],
            'sender_id' => $attributes['sender_id'] ?? null,
            'target_type' => $attributes['target_type'],
            'notification_category' => UserNotificationPreference::normalizeCategory($attributes['notification_category'] ?? null),
            'target_payload' => $attributes['target_payload'] ?? [],
            'locale' => $attributes['locale'] ?? 'ar',
            'title' => $attributes['title'],
            'body' => $attributes['body'],
            'image_url' => $attributes['image_url'] ?? null,
            'status' => $isDueNow
                ? NotificationCampaign::STATUS_PENDING
                : NotificationCampaign::STATUS_SCHEDULED,
            'queued_at' => $isDueNow ? now() : null,
            'scheduled_at' => $scheduledAt,
        ]);

        if ($isDueNow) {
            DispatchNotificationCampaignJob::dispatch($campaign->id)
                ->onQueue((string) config('notification_campaigns.queue', 'notifications'));
        }

        return $campaign;
    }
}
