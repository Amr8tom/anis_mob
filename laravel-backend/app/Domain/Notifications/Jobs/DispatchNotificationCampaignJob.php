<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Jobs;

use App\Domain\Notifications\Services\NotificationAudienceQueryBuilder;
use App\Domain\Notifications\Services\NotificationRateLimitService;
use App\Models\DeviceToken;
use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class DispatchNotificationCampaignJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly string $campaignId)
    {
        $this->onQueue((string) config('notification_campaigns.queue', 'notifications'));
    }

    public function handle(
        NotificationAudienceQueryBuilder $audience,
        NotificationRateLimitService $rateLimits,
    ): void
    {
        $campaign = NotificationCampaign::find($this->campaignId);

        if ($campaign === null || $campaign->status !== NotificationCampaign::STATUS_PENDING) {
            return;
        }

        $campaign->forceFill([
            'status' => NotificationCampaign::STATUS_BUILDING,
            'started_at' => $campaign->started_at ?? now(),
        ])->save();

        $batchSize = max(1, (int) config('notification_campaigns.batch_size', 500));
        $now = now();
        $queuedAnyRecipient = false;
        $queuedAnyPendingRecipient = false;

        $audience->deviceTokensForCampaign($campaign)
            ->select(['id', 'user_id'])
            ->chunkById($batchSize, function ($tokens) use ($campaign, $now, $rateLimits, &$queuedAnyRecipient, &$queuedAnyPendingRecipient): void {
                $limitedUserIds = $rateLimits->limitedUserIdsForCampaign(
                    $campaign,
                    $tokens->pluck('user_id'),
                );

                $limitedLookup = array_fill_keys($limitedUserIds, true);
                $pendingTokens = collect();
                $skippedRows = [];

                foreach ($tokens as $token) {
                    if (isset($limitedLookup[$token->user_id])) {
                        $skippedRows[] = $this->recipientRow(
                            $campaign,
                            $token,
                            $now,
                            NotificationRecipient::STATUS_SKIPPED,
                            'daily_recipient_limit',
                            'The workspace owner already reached the daily notification limit for this user.',
                        );

                        continue;
                    }

                    $pendingTokens->push($token);
                }

                $pendingRows = $pendingTokens->map(fn (DeviceToken $token): array => $this->recipientRow(
                    $campaign,
                    $token,
                    $now,
                    NotificationRecipient::STATUS_PENDING,
                ))->all();

                $rows = array_merge($pendingRows, $skippedRows);

                if ($rows === []) {
                    return;
                }

                $inserted = DB::table('notification_recipients')->insertOrIgnore($rows);

                if ($inserted <= 0) {
                    return;
                }

                $queuedAnyRecipient = true;

                NotificationCampaign::whereKey($campaign->id)->increment('targeted_count', $inserted);

                if ($skippedRows !== []) {
                    NotificationCampaign::whereKey($campaign->id)->increment('skipped_count', count($skippedRows));
                }

                if ($pendingTokens->isEmpty()) {
                    return;
                }

                NotificationCampaign::whereKey($campaign->id)->update([
                    'status' => NotificationCampaign::STATUS_SENDING,
                ]);

                $queuedAnyPendingRecipient = true;

                SendNotificationBatchJob::dispatch(
                    $campaign->id,
                    $pendingTokens->pluck('id')->values()->all(),
                )->onQueue((string) config('notification_campaigns.queue', 'notifications'));
            });

        if (! $queuedAnyRecipient) {
            $campaign->refresh();

            if ($campaign->targeted_count === 0) {
                $campaign->forceFill([
                    'status' => NotificationCampaign::STATUS_FAILED,
                    'completed_at' => now(),
                    'error_message' => 'No active recipients with devices found.',
                ])->save();
            }

            return;
        }

        if (! $queuedAnyPendingRecipient) {
            $this->finalizeCampaignIfComplete($campaign->id);
        }
    }

    private function recipientRow(
        NotificationCampaign $campaign,
        DeviceToken $token,
        mixed $now,
        string $status,
        ?string $errorCode = null,
        ?string $errorMessage = null,
    ): array {
        return [
            'id' => (string) Str::uuid(),
            'campaign_id' => $campaign->id,
            'user_id' => $token->user_id,
            'device_token_id' => $token->id,
            'locale' => $campaign->locale,
            'status' => $status,
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    private function finalizeCampaignIfComplete(string $campaignId): void
    {
        $campaign = NotificationCampaign::find($campaignId);

        if ($campaign === null || $campaign->targeted_count === 0) {
            return;
        }

        $processedCount = $campaign->sent_count + $campaign->failed_count + $campaign->skipped_count;

        if ($processedCount < $campaign->targeted_count) {
            return;
        }

        $campaign->forceFill([
            'status' => $campaign->failed_count > 0
                ? NotificationCampaign::STATUS_FAILED
                : NotificationCampaign::STATUS_SENT,
            'completed_at' => $campaign->completed_at ?? now(),
        ])->save();
    }
}
