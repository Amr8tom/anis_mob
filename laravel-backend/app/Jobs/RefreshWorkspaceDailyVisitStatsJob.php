<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\WorkspaceSettlement\Actions\RefreshWorkspaceDailyVisitStatsAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

final class RefreshWorkspaceDailyVisitStatsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function __construct(public string $workspaceId, public string $date)
    {
        $this->onQueue('reports');
    }

    public function handle(RefreshWorkspaceDailyVisitStatsAction $action): void
    {
        $action->handle($this->workspaceId, Carbon::parse($this->date));
    }
}
