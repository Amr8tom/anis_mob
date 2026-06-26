<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Jobs;

use App\Domain\Notifications\Notifiables\DeviceTokenNotifiable;
use App\Models\DeviceToken;
use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use App\Notifications\AdminBroadcastNotification;
use App\Notifications\WorkspaceBroadcastNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

final class SendNotificationBatchJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    /**
     * @param array<int, string> $deviceTokenIds
     */
    public function __construct(
        public readonly string $campaignId,
        public readonly array $deviceTokenIds,
    ) {
        $this->onQueue((string) config('notification_campaigns.queue', 'notifications'));
    }

    public function handle(): void
    {
        $campaign = NotificationCampaign::find($this->campaignId);

        if ($campaign === null) {
            return;
        }

        $tokens = DeviceToken::query()
            ->whereIn('id', $this->deviceTokenIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $recipients = NotificationRecipient::query()
            ->where('campaign_id', $this->campaignId)
            ->whereIn('device_token_id', $this->deviceTokenIds)
            ->get()
            ->keyBy('device_token_id');

        $sent = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($this->deviceTokenIds as $deviceTokenId) {
            $token = $tokens->get($deviceTokenId);
            $recipient = $recipients->get($deviceTokenId);

            if (! $token instanceof DeviceToken || ! $recipient instanceof NotificationRecipient) {
                $skipped++;
                $this->markRecipientSkipped($deviceTokenId);
                continue;
            }

            try {
                $notification = $this->notificationFor($campaign, $recipient);
                Notification::sendNow(new DeviceTokenNotifiable($token), $notification);

                $sent++;
                $token->forceFill([
                    'failure_count' => 0,
                    'last_used_at' => now(),
                ])->save();

                NotificationRecipient::query()
                    ->whereKey($recipient->id)
                    ->where('status', NotificationRecipient::STATUS_PENDING)
                    ->update([
                        'status' => NotificationRecipient::STATUS_SENT,
                        'sent_at' => now(),
                        'updated_at' => now(),
                    ]);
            } catch (Throwable $exception) {
                report($exception);

                $failed++;
                $this->recordTokenFailure($token, $exception);
                $this->markRecipientFailed($deviceTokenId, $exception);
            }
        }

        $this->incrementCampaignCounts($sent, $failed, $skipped);
        $this->finalizeCampaignIfComplete();
    }

    private function notificationFor(NotificationCampaign $campaign, NotificationRecipient $recipient): AdminBroadcastNotification|WorkspaceBroadcastNotification
    {
        $data = array_merge($campaign->target_payload['notification_data'] ?? [], [
            'campaign_id' => $campaign->id,
            'recipient_id' => $recipient->id,
            'notification_category' => $campaign->notification_category,
        ]);

        if ($campaign->sender_type === 'admin') {
            return new AdminBroadcastNotification($campaign->title, $campaign->body, $campaign->image_url, $data);
        }

        return new WorkspaceBroadcastNotification(
            $campaign->title,
            $campaign->body,
            $campaign->image_url,
            (string) $campaign->workspace_id,
            $data,
        );
    }

    private function markRecipientSkipped(string $deviceTokenId): void
    {
        NotificationRecipient::query()
            ->where('campaign_id', $this->campaignId)
            ->where('device_token_id', $deviceTokenId)
            ->where('status', NotificationRecipient::STATUS_PENDING)
            ->update([
                'status' => NotificationRecipient::STATUS_SKIPPED,
                'error_message' => 'Device token is no longer active.',
                'updated_at' => now(),
            ]);
    }

    private function markRecipientFailed(string $deviceTokenId, Throwable $exception): void
    {
        NotificationRecipient::query()
            ->where('campaign_id', $this->campaignId)
            ->where('device_token_id', $deviceTokenId)
            ->where('status', NotificationRecipient::STATUS_PENDING)
            ->update([
                'status' => NotificationRecipient::STATUS_FAILED,
                'error_code' => $this->guessErrorCode($exception),
                'error_message' => mb_substr($exception->getMessage(), 0, 1000),
                'updated_at' => now(),
            ]);
    }

    private function recordTokenFailure(DeviceToken $token, Throwable $exception): void
    {
        $failureCount = $token->failure_count + 1;

        $token->forceFill([
            'failure_count' => $failureCount,
            'last_failed_at' => now(),
            'is_active' => $this->isPermanentTokenFailure($exception) ? false : $token->is_active,
        ])->save();
    }

    private function incrementCampaignCounts(int $sent, int $failed, int $skipped): void
    {
        NotificationCampaign::query()
            ->whereKey($this->campaignId)
            ->update([
                'sent_count' => DB::raw('sent_count + '.$sent),
                'failed_count' => DB::raw('failed_count + '.$failed),
                'skipped_count' => DB::raw('skipped_count + '.$skipped),
                'updated_at' => now(),
            ]);
    }

    private function finalizeCampaignIfComplete(): void
    {
        $campaign = NotificationCampaign::find($this->campaignId);

        if ($campaign === null || $campaign->targeted_count === 0) {
            return;
        }

        $processedCount = $campaign->sent_count + $campaign->failed_count + $campaign->skipped_count;

        if ($processedCount < $campaign->targeted_count) {
            return;
        }

        $status = $campaign->failed_count > 0
            ? NotificationCampaign::STATUS_FAILED
            : NotificationCampaign::STATUS_SENT;

        $campaign->forceFill([
            'status' => $status,
            'completed_at' => $campaign->completed_at ?? now(),
        ])->save();
    }

    private function isPermanentTokenFailure(Throwable $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'not registered')
            || str_contains($message, 'registration-token-not-registered')
            || str_contains($message, 'invalid registration')
            || str_contains($message, 'invalid token')
            || str_contains($message, 'requested entity was not found');
    }

    private function guessErrorCode(Throwable $exception): string
    {
        if ($this->isPermanentTokenFailure($exception)) {
            return 'invalid_token';
        }

        return 'send_failed';
    }
}
