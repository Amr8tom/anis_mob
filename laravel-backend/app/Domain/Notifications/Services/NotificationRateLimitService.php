<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Services;

use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use Illuminate\Support\Collection;

final class NotificationRateLimitService
{
    public function workspaceDailyLimit(): int
    {
        return max(1, (int) config('notification_campaigns.workspace_daily_limit', 50));
    }

    public function perUserDailyLimit(): int
    {
        return max(1, (int) config('notification_campaigns.per_user_daily_limit', 5));
    }

    public function workspaceCampaignLimitReached(string $workspaceId): bool
    {
        return NotificationCampaign::query()
            ->where('workspace_id', $workspaceId)
            ->where('sender_type', 'workspace_owner')
            ->where('created_at', '>=', now()->startOfDay())
            ->count() >= $this->workspaceDailyLimit();
    }

    /**
     * @param Collection<int, string|null> $userIds
     * @return array<int, string>
     */
    public function limitedUserIdsForCampaign(NotificationCampaign $campaign, Collection $userIds): array
    {
        if ($campaign->sender_type !== 'workspace_owner' || $campaign->workspace_id === null) {
            return [];
        }

        $ids = $userIds
            ->filter(fn ($id): bool => is_string($id) && $id !== '')
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return [];
        }

        return NotificationRecipient::query()
            ->join('notification_campaigns', 'notification_campaigns.id', '=', 'notification_recipients.campaign_id')
            ->where('notification_campaigns.workspace_id', $campaign->workspace_id)
            ->where('notification_campaigns.sender_type', 'workspace_owner')
            ->where('notification_recipients.created_at', '>=', now()->startOfDay())
            ->whereIn('notification_recipients.user_id', $ids->all())
            ->whereIn('notification_recipients.status', [
                NotificationRecipient::STATUS_PENDING,
                NotificationRecipient::STATUS_SENT,
            ])
            ->select('notification_recipients.user_id')
            ->groupBy('notification_recipients.user_id')
            ->havingRaw('COUNT(DISTINCT notification_recipients.campaign_id) >= ?', [$this->perUserDailyLimit()])
            ->pluck('notification_recipients.user_id')
            ->filter()
            ->values()
            ->all();
    }
}
