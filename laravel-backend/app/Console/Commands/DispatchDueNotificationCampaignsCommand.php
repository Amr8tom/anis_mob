<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Notifications\Jobs\DispatchNotificationCampaignJob;
use App\Models\NotificationCampaign;
use Illuminate\Console\Command;

final class DispatchDueNotificationCampaignsCommand extends Command
{
    protected $signature = 'notifications:dispatch-due-campaigns {--limit=1000 : Maximum scheduled campaigns to queue per run}';

    protected $description = 'Queue notification campaigns whose scheduled send time has arrived.';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));
        $queued = 0;

        NotificationCampaign::query()
            ->where('status', NotificationCampaign::STATUS_SCHEDULED)
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get(['id'])
            ->each(function (NotificationCampaign $campaign) use (&$queued): void {
                $updated = NotificationCampaign::query()
                    ->whereKey($campaign->id)
                    ->where('status', NotificationCampaign::STATUS_SCHEDULED)
                    ->where('scheduled_at', '<=', now())
                    ->update([
                        'status' => NotificationCampaign::STATUS_PENDING,
                        'queued_at' => now(),
                        'updated_at' => now(),
                    ]);

                if ($updated !== 1) {
                    return;
                }

                DispatchNotificationCampaignJob::dispatch($campaign->id)
                    ->onQueue((string) config('notification_campaigns.queue', 'notifications'));

                $queued++;
            });

        $this->info("Queued {$queued} scheduled notification campaign(s).");

        return self::SUCCESS;
    }
}
