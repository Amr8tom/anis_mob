<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\WorkspaceSettlement\Actions\RefreshWorkspaceDailyVisitStatsAction;
use App\Models\WorkspaceVisit;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final class RollupWorkspaceVisitStatsCommand extends Command
{
    protected $signature = 'reports:rollup-visits {--from=} {--to=}';

    protected $description = 'Refresh daily workspace visit reporting rollups';

    public function handle(RefreshWorkspaceDailyVisitStatsAction $action): int
    {
        $from = Carbon::parse($this->option('from') ?: yesterday()->toDateString())->startOfDay();
        $to = Carbon::parse($this->option('to') ?: $from->toDateString())->startOfDay();
        $processed = 0;

        for ($date = $from->copy(); $date->lessThanOrEqualTo($to); $date->addDay()) {
            $dayStart = $date->copy()->startOfDay();
            WorkspaceVisit::query()
                ->whereNotNull('check_out_at')
                ->where('check_out_at', '>=', $dayStart)
                ->where('check_out_at', '<', $dayStart->copy()->addDay())
                ->select('workspace_id')
                ->distinct()
                ->orderBy('workspace_id')
                ->lazy(500)
                ->each(function (WorkspaceVisit $visit) use ($action, $dayStart, &$processed): void {
                    $action->handle($visit->workspace_id, $dayStart);
                    $processed++;
                });
        }

        Log::info('workspace_rollups.refresh.completed', [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'processed' => $processed,
        ]);

        return self::SUCCESS;
    }
}
