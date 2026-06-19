<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ExpireSubscriptionsCommand extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Mark subscriptions past their expiry date as EXPIRED';

    public function handle(): int
    {
        $this->info('Finding expired subscriptions...');

        $expiredCount = Subscription::where('status', SubscriptionStatus::ACTIVE)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update([
                'status' => SubscriptionStatus::EXPIRED,
                'active_flag' => null,
            ]);

        $this->info("Expired {$expiredCount} subscriptions.");

        return self::SUCCESS;
    }
}
