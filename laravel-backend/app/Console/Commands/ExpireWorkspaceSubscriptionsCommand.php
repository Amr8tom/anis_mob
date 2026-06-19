<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\WorkspaceSubscription\Actions\ExpireWorkspaceSubscriptionsAction;
use Illuminate\Console\Command;

final class ExpireWorkspaceSubscriptionsCommand extends Command
{
    protected $signature = 'workspace-subscriptions:expire';

    protected $description = 'Expire workspace subscriptions past their end date (skipping ones funding an open visit)';

    public function handle(ExpireWorkspaceSubscriptionsAction $action): int
    {
        $count = $action->handle();

        $this->info("Expired {$count} workspace subscriptions.");

        return self::SUCCESS;
    }
}
