<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class WorkspaceScalingHealthCommand extends Command
{
    protected $signature = 'workspace-scaling:health {--retention-threshold=10000}';

    protected $description = 'Check workspace scaling summaries, rollups, retention backlog, and reports queue depth';

    public function handle(): int
    {
        $requiredTables = ['workspace_clients', 'workspace_visit_archives', 'workspace_daily_visit_stats', 'workspace_visits'];
        foreach ($requiredTables as $table) {
            if (! Schema::hasTable($table)) {
                $this->error("Missing required table: {$table}");

                return self::FAILURE;
            }
        }

        $uncounted = $this->uncountedVisits();
        $oldestUncounted = $this->oldestUncountedVisit();
        $missingRollups = $this->missingRollupRows();
        $latestRollup = DB::table('workspace_daily_visit_stats')->max('stat_date');
        $retentionEligible = $this->retentionEligibleCount();
        $reportsQueueDepth = $this->reportsQueueDepth();

        $rows = [
            ['Checked-out visits not counted in workspace_clients', $uncounted],
            ['Oldest uncounted checked-out visit', $oldestUncounted ?? 'none'],
            ['workspace_clients rows', DB::table('workspace_clients')->count()],
            ['workspace_visit_archives rows', DB::table('workspace_visit_archives')->count()],
            ['Missing rollup rows before today', $missingRollups],
            ['Latest rollup date', $latestRollup ?? 'none'],
            ['Retention eligible raw visits', $retentionEligible],
            ['Reports queue depth', $reportsQueueDepth ?? 'not database queue'],
        ];

        $this->table(['Metric', 'Value'], $rows);

        $threshold = max(0, (int) $this->option('retention-threshold'));
        $critical = $uncounted > 0 || $missingRollups > 0 || $retentionEligible > $threshold;
        if ($critical) {
            $this->error('Workspace scaling health check failed.');

            return self::FAILURE;
        }

        $this->info('Workspace scaling health check passed.');

        return self::SUCCESS;
    }

    private function uncountedVisits(): int
    {
        return (int) DB::table('workspace_visits')
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereNotNull('check_out_at')
            ->whereNull('workspace_client_counted_at')
            ->count();
    }

    private function oldestUncountedVisit(): ?string
    {
        $oldest = DB::table('workspace_visits')
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereNotNull('check_out_at')
            ->whereNull('workspace_client_counted_at')
            ->min('check_out_at');

        return $oldest === null ? null : (string) $oldest;
    }

    private function missingRollupRows(): int
    {
        $visitDays = DB::table('workspace_visits')
            ->selectRaw('workspace_id, DATE(check_out_at) as stat_date, COALESCE(billing_source, ?) as billing_source', [BillingSource::FREE->value])
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereNotNull('check_out_at')
            ->where('check_out_at', '<', today())
            ->groupBy('workspace_id', DB::raw('DATE(check_out_at)'), DB::raw("COALESCE(billing_source, '".BillingSource::FREE->value."')"));

        return (int) DB::query()
            ->fromSub($visitDays, 'visit_days')
            ->leftJoin('workspace_daily_visit_stats as stats', function ($join): void {
                $join->on('stats.workspace_id', '=', 'visit_days.workspace_id')
                    ->on('stats.billing_source', '=', 'visit_days.billing_source')
                    ->whereRaw('DATE(stats.stat_date) = visit_days.stat_date');
            })
            ->whereNull('stats.id')
            ->count();
    }

    private function retentionEligibleCount(): int
    {
        return (int) DB::table('workspace_visits')
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereNotNull('check_out_at')
            ->whereNotNull('workspace_client_counted_at')
            ->where('check_out_at', '<', now()->subYear())
            ->count();
    }

    private function reportsQueueDepth(): ?int
    {
        if (config('queue.default') !== 'database') {
            return null;
        }

        $table = (string) config('queue.connections.database.table', 'jobs');
        if (! Schema::hasTable($table)) {
            return null;
        }

        return (int) DB::table($table)->where('queue', 'reports')->count();
    }
}
