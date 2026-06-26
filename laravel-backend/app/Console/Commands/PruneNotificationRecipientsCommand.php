<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use Illuminate\Console\Command;

final class PruneNotificationRecipientsCommand extends Command
{
    protected $signature = 'notifications:prune-recipients
        {--days= : Retention window in days, capped at 60}
        {--chunk=1000 : Rows to delete per chunk}';

    protected $description = 'Delete old per-device notification recipient details while keeping campaign summaries.';

    public function handle(): int
    {
        $configuredDays = (int) ($this->option('days') ?: config('notification_campaigns.retention_days', 60));
        $days = min(60, max(1, $configuredDays));
        $chunkSize = max(100, (int) $this->option('chunk'));
        $cutoff = now()->subDays($days);
        $deleted = 0;

        do {
            $ids = NotificationRecipient::query()
                ->where('created_at', '<', $cutoff)
                ->limit($chunkSize)
                ->pluck('id');

            if ($ids->isEmpty()) {
                break;
            }

            $deleted += NotificationRecipient::query()
                ->whereIn('id', $ids->all())
                ->delete();
        } while (true);

        $marked = NotificationCampaign::query()
            ->whereNull('recipients_pruned_at')
            ->where('created_at', '<', $cutoff)
            ->update([
                'recipients_pruned_at' => now(),
                'updated_at' => now(),
            ]);

        $this->info("Deleted {$deleted} old recipient row(s), marked {$marked} campaign(s) as pruned.");

        return self::SUCCESS;
    }
}
