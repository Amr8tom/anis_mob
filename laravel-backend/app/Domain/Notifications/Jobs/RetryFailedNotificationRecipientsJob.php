<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Jobs;

use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

final class RetryFailedNotificationRecipientsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly string $campaignId)
    {
        $this->onQueue((string) config('notification_campaigns.queue', 'notifications'));
    }

    public function handle(): void
    {
        $campaign = NotificationCampaign::find($this->campaignId);

        if ($campaign === null) {
            return;
        }

        NotificationRecipient::query()
            ->where('campaign_id', $campaign->id)
            ->where('status', NotificationRecipient::STATUS_FAILED)
            ->whereNotNull('device_token_id')
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('device_tokens')
                    ->whereColumn('device_tokens.id', 'notification_recipients.device_token_id')
                    ->where('device_tokens.is_active', true);
            })
            ->select(['id', 'device_token_id'])
            ->chunkById(500, function ($recipients) use ($campaign): void {
                $recipientIds = $recipients->pluck('id')->all();
                $deviceTokenIds = $recipients->pluck('device_token_id')->filter()->values()->all();

                if ($recipientIds === [] || $deviceTokenIds === []) {
                    return;
                }

                $updated = NotificationRecipient::query()
                    ->whereIn('id', $recipientIds)
                    ->where('status', NotificationRecipient::STATUS_FAILED)
                    ->update([
                        'status' => NotificationRecipient::STATUS_PENDING,
                        'error_code' => null,
                        'error_message' => null,
                        'updated_at' => now(),
                    ]);

                if ($updated <= 0) {
                    return;
                }

                NotificationCampaign::query()
                    ->whereKey($campaign->id)
                    ->update([
                        'status' => NotificationCampaign::STATUS_SENDING,
                        'failed_count' => DB::raw('CASE WHEN failed_count >= '.$updated.' THEN failed_count - '.$updated.' ELSE 0 END'),
                        'completed_at' => null,
                        'updated_at' => now(),
                    ]);

                SendNotificationBatchJob::dispatch($campaign->id, $deviceTokenIds)
                    ->onQueue((string) config('notification_campaigns.queue', 'notifications'));
            });
    }
}
