<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSettlement\Actions;

use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use App\Models\Workspace;
use App\Models\WorkspaceDailyVisitStat;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Carbon;

final readonly class GetWorkspaceVisitReportAction
{
    /**
     * Returns a stats summary for the given workspace and date window.
     *
     * Keys returned:
     *   total_visits, total_minutes, total_hours (float), unique_visitors
     *   free_visits,   free_minutes,   free_visitors
     *   silver_visits, silver_minutes, silver_visitors
     *   gold_visits,   gold_minutes,   gold_visitors
     *
     * @return array<string, int|float>
     */
    public function handle(Workspace $workspace, Carbon $from, Carbon $toExclusive): array
    {
        $useRollups = $this->canUseRollups($workspace->id, $from, $toExclusive);

        // ── Per-tier visit counts + minutes ──────────────────────────────────
        if ($useRollups) {
            $rows = WorkspaceDailyVisitStat::query()
                ->where('workspace_id', $workspace->id)
                ->where('stat_date', '>=', $from->toDateString())
                ->where('stat_date', '<', $toExclusive->toDateString())
                ->selectRaw('billing_source as source')
                ->selectRaw('SUM(visits_count) as visits_count')
                ->selectRaw('SUM(unique_visitors_count) as visitors_count')
                ->selectRaw('SUM(total_minutes) as total_minutes')
                ->groupBy('billing_source')
                ->get()
                ->keyBy('source');
        } else {
            $rows = WorkspaceVisit::query()
                ->where('workspace_id', $workspace->id)
                ->where('status', VisitStatus::CHECKED_OUT->value)
                ->where('check_out_at', '>=', $from)
                ->where('check_out_at', '<', $toExclusive)
                ->selectRaw("COALESCE(billing_source, 'FREE') as source")
                ->selectRaw('COUNT(*) as visits_count')
                ->selectRaw('COALESCE(SUM(billable_minutes), 0) as total_minutes')
                ->groupByRaw("COALESCE(billing_source, 'FREE')")
                ->get()
                ->keyBy('source');
        }

        if ($useRollups) {
            $freeVisitors = (int) ($rows->get(BillingSource::FREE->value)?->visitors_count ?? 0);
            $globalVisitors = (int) ($rows->get(BillingSource::GLOBAL_SUBSCRIPTION->value)?->visitors_count ?? 0);
            $workspaceVisitors = (int) ($rows->get(BillingSource::WORKSPACE_SUBSCRIPTION->value)?->visitors_count ?? 0);
            $allVisitors = $freeVisitors + $globalVisitors + $workspaceVisitors;
        } else {
            $allVisitors = $this->distinctVisitors($workspace->id, $from, $toExclusive);
            $freeVisitors = $this->distinctVisitors($workspace->id, $from, $toExclusive, BillingSource::FREE->value);
            $globalVisitors = $this->distinctVisitors($workspace->id, $from, $toExclusive, BillingSource::GLOBAL_SUBSCRIPTION->value);
            $workspaceVisitors = $this->distinctVisitors($workspace->id, $from, $toExclusive, BillingSource::WORKSPACE_SUBSCRIPTION->value);
        }

        // ── Assemble summary ─────────────────────────────────────────────────
        $summary = [
            'total_visits' => 0,
            'total_minutes' => 0,
            'total_hours' => 0.0,
            'unique_visitors' => $allVisitors,

            'free_visits' => 0,
            'free_minutes' => 0,
            'free_visitors' => $freeVisitors,

            'global_subscription_visits' => 0,
            'global_subscription_minutes' => 0,
            'global_subscription_visitors' => $globalVisitors,

            'workspace_subscription_visits' => 0,
            'workspace_subscription_minutes' => 0,
            'workspace_subscription_visitors' => $workspaceVisitors,
        ];

        foreach ([BillingSource::FREE->value, BillingSource::GLOBAL_SUBSCRIPTION->value, BillingSource::WORKSPACE_SUBSCRIPTION->value] as $source) {
            $row = $rows->get($source);
            $visits = (int) ($row?->visits_count ?? 0);
            $mins = (int) ($row?->total_minutes ?? 0);
            $key = strtolower($source);

            $summary["{$key}_visits"] = $visits;
            $summary["{$key}_minutes"] = $mins;
            $summary['total_visits'] += $visits;
            $summary['total_minutes'] += $mins;
        }

        $summary['total_hours'] = round($summary['total_minutes'] / 60, 2);

        return $summary;
    }

    private function canUseRollups(string $workspaceId, Carbon $from, Carbon $toExclusive): bool
    {
        if (! $from->isStartOfDay() || ! $toExclusive->isStartOfDay() || $toExclusive->greaterThan(today()->startOfDay())) {
            return false;
        }

        return WorkspaceDailyVisitStat::query()
            ->where('workspace_id', $workspaceId)
            ->where('stat_date', '>=', $from->toDateString())
            ->where('stat_date', '<', $toExclusive->toDateString())
            ->count() === $from->diffInDays($toExclusive) * 3;
    }

    private function distinctVisitors(string $workspaceId, Carbon $from, Carbon $toExclusive, ?string $source = null): int
    {
        $base = WorkspaceVisit::query()
            ->where('workspace_id', $workspaceId)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_out_at', '>=', $from)
            ->where('check_out_at', '<', $toExclusive)
            ->when($source, function ($query, $source) {
                if ($source === BillingSource::FREE->value) {
                    $query->where(function ($q) {
                        $q->where('billing_source', BillingSource::FREE->value)
                            ->orWhereNull('billing_source');
                    });
                } else {
                    $query->where('billing_source', $source);
                }
            });

        return (clone $base)->whereNotNull('user_id')->distinct()->count('user_id')
            + (clone $base)->whereNotNull('walk_in_id')->distinct()->count('walk_in_id');
    }
}
