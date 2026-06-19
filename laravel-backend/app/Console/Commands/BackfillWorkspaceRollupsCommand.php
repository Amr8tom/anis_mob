<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\WorkspaceSettlement\Actions\RefreshWorkspaceDailyVisitStatsAction;
use App\Enums\VisitStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class BackfillWorkspaceRollupsCommand extends Command
{
    protected $signature = 'reports:backfill-rollups
        {--from= : Include visits checked out on or after this date}
        {--to= : Include visits checked out on or before this date}
        {--workspace= : Only process one workspace ID}
        {--chunk=500 : Number of workspace/day pairs to scan per chunk}
        {--dry-run : Count workspace/day pairs without refreshing rollups}';

    protected $description = 'Backfill daily workspace visit rollups for historical checked-out visits';

    public function handle(RefreshWorkspaceDailyVisitStatsAction $refresh): int
    {
        $from = Carbon::parse($this->option('from') ?: yesterday()->toDateString())->startOfDay();
        $to = Carbon::parse($this->option('to') ?: $from->toDateString())->endOfDay();
        $chunk = max(1, min(5000, (int) $this->option('chunk')));
        $dryRun = (bool) $this->option('dry-run');

        $pairs = DB::table('workspace_visits')
            ->selectRaw('workspace_id, DATE(check_out_at) as stat_date')
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereNotNull('check_out_at')
            ->where('check_out_at', '>=', $from)
            ->where('check_out_at', '<=', $to)
            ->when($this->option('workspace'), fn ($query, string $workspaceId) => $query->where('workspace_id', $workspaceId))
            ->groupBy('workspace_id', DB::raw('DATE(check_out_at)'))
            ->orderBy('stat_date')
            ->orderBy('workspace_id')
            ->get();

        $processed = 0;
        foreach ($pairs->chunk($chunk) as $chunkedPairs) {
            foreach ($chunkedPairs as $pair) {
                $processed++;
                if (! $dryRun) {
                    $refresh->handle((string) $pair->workspace_id, Carbon::parse($pair->stat_date));
                }
            }
        }

        $mode = $dryRun ? 'would process' : 'processed';
        $this->info("Rollup backfill {$mode} {$processed} workspace/day pairs.");
        Log::info('workspace_rollups.backfill.completed', [
            'processed' => $processed,
            'dry_run' => $dryRun,
            'workspace_id' => $this->option('workspace'),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);

        return self::SUCCESS;
    }
}
