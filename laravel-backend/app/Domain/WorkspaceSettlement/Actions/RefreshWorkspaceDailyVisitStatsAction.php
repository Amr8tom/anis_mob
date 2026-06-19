<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSettlement\Actions;

use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use App\Models\WorkspaceDailyVisitStat;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Carbon;

final readonly class RefreshWorkspaceDailyVisitStatsAction
{
    public function handle(string $workspaceId, Carbon $date): void
    {
        $start = $date->copy()->startOfDay();
        $end = $start->copy()->addDay();
        $rows = WorkspaceVisit::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_out_at', '>=', $start)
            ->where('check_out_at', '<', $end)
            ->selectRaw("COALESCE(billing_source, 'FREE') as source")
            ->selectRaw('COUNT(*) as visits_count')
            ->selectRaw('COUNT(DISTINCT user_id) as registered_visitors_count')
            ->selectRaw('COUNT(DISTINCT walk_in_id) as walk_in_visitors_count')
            ->selectRaw('COUNT(DISTINCT user_id) + COUNT(DISTINCT walk_in_id) as visitors_count')
            ->selectRaw('COALESCE(SUM(billable_minutes), 0) as total_minutes')
            ->groupByRaw("COALESCE(billing_source, 'FREE')")
            ->get()
            ->keyBy('source');

        foreach ([BillingSource::FREE->value, BillingSource::GLOBAL_SUBSCRIPTION->value, BillingSource::WORKSPACE_SUBSCRIPTION->value] as $source) {
            $row = $rows->get($source);
            WorkspaceDailyVisitStat::updateOrCreate(
                ['workspace_id' => $workspaceId, 'stat_date' => $start->toDateString(), 'billing_source' => $source],
                [
                    'visits_count' => (int) ($row?->visits_count ?? 0),
                    'visitors_count' => (int) ($row?->visitors_count ?? 0),
                    'unique_visitors_count' => (int) ($row?->visitors_count ?? 0),
                    'registered_visitors_count' => (int) ($row?->registered_visitors_count ?? 0),
                    'walk_in_visitors_count' => (int) ($row?->walk_in_visitors_count ?? 0),
                    'total_minutes' => (int) ($row?->total_minutes ?? 0),
                ],
            );
        }
    }
}
