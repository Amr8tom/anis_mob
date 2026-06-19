<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use App\Models\WorkspaceClient;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class RepairWorkspaceClientsCommand extends Command
{
    protected $signature = 'workspace-clients:repair
        {--workspace= : Only repair one workspace ID}
        {--client= : Repair a specific workspace_clients id, user_id, or walk_in_id}
        {--dry-run : Report affected clients without writing}';

    protected $description = 'Recalculate workspace client summary counters from raw checked-out visits';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $clientFilter = $this->option('client') ? (string) $this->option('client') : null;
        $workspaceClient = $clientFilter !== null
            ? WorkspaceClient::query()->whereKey($clientFilter)->first()
            : null;

        $groups = DB::table('workspace_visits')
            ->select('workspace_id', 'user_id', 'walk_in_id')
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where(function ($query): void {
                $query->whereNotNull('user_id')->orWhereNotNull('walk_in_id');
            })
            ->when($this->option('workspace'), fn ($query, string $workspaceId) => $query->where('workspace_id', $workspaceId))
            ->when($workspaceClient !== null, function ($query) use ($workspaceClient): void {
                $query->where('workspace_id', $workspaceClient->workspace_id)
                    ->when(
                        $workspaceClient->user_id !== null,
                        fn ($query) => $query->where('user_id', $workspaceClient->user_id),
                        fn ($query) => $query->where('walk_in_id', $workspaceClient->walk_in_id),
                    );
            })
            ->when($workspaceClient === null && $clientFilter !== null, function ($query) use ($clientFilter): void {
                $query->where(function ($query) use ($clientFilter): void {
                    $query->where('user_id', $clientFilter)->orWhere('walk_in_id', $clientFilter);
                });
            })
            ->groupBy('workspace_id', 'user_id', 'walk_in_id')
            ->orderBy('workspace_id')
            ->get();

        $repaired = 0;
        foreach ($groups as $group) {
            $summary = $this->summaryForGroup($group);
            if ($summary === null) {
                continue;
            }

            $repaired++;
            if ($dryRun) {
                continue;
            }

            DB::transaction(function () use ($group, $summary): void {
                $client = WorkspaceClient::query()
                    ->where('workspace_id', $group->workspace_id)
                    ->when(
                        $group->user_id !== null,
                        fn ($query) => $query->where('user_id', $group->user_id),
                        fn ($query) => $query->where('walk_in_id', $group->walk_in_id),
                    )
                    ->lockForUpdate()
                    ->first();

                $client ??= new WorkspaceClient([
                    'workspace_id' => $group->workspace_id,
                    'user_id' => $group->user_id,
                    'walk_in_id' => $group->walk_in_id,
                    'client_type' => $group->user_id !== null ? 'USER' : 'WALK_IN',
                ]);

                $client->fill($summary);
                $client->save();

                $this->groupVisitsQuery($group)
                    ->whereNull('workspace_client_counted_at')
                    ->update(['workspace_client_counted_at' => now()]);
            });
        }

        $mode = $dryRun ? 'would repair' : 'repaired';
        $this->info("Workspace clients {$mode} {$repaired} rows.");
        Log::info('workspace_clients.repair.completed', [
            'repaired' => $repaired,
            'dry_run' => $dryRun,
            'workspace_id' => $this->option('workspace'),
            'client' => $this->option('client'),
        ]);

        return self::SUCCESS;
    }

    /** @return array<string, mixed>|null */
    private function summaryForGroup(object $group): ?array
    {
        $row = $this->groupVisitsQuery($group)
            ->selectRaw('COUNT(*) as total_visits')
            ->selectRaw('COALESCE(SUM(duration_minutes), 0) as total_minutes')
            ->selectRaw('MIN(check_in_at) as first_visit_at')
            ->selectRaw('MAX(COALESCE(check_out_at, check_in_at)) as last_visit_at')
            ->selectRaw('SUM(CASE WHEN COALESCE(billing_source, ?) = ? THEN 1 ELSE 0 END) as free_visits', [BillingSource::FREE->value, BillingSource::FREE->value])
            ->selectRaw('SUM(CASE WHEN billing_source = ? THEN 1 ELSE 0 END) as global_subscription_visits', [BillingSource::GLOBAL_SUBSCRIPTION->value])
            ->selectRaw('SUM(CASE WHEN billing_source = ? THEN 1 ELSE 0 END) as workspace_subscription_visits', [BillingSource::WORKSPACE_SUBSCRIPTION->value])
            ->first();

        if ($row === null || (int) $row->total_visits === 0) {
            return null;
        }

        $identity = $group->user_id !== null
            ? DB::table('users')->where('id', $group->user_id)->first(['full_name', 'phone_number'])
            : DB::table('workspace_walk_ins')->where('id', $group->walk_in_id)->first(['full_name', 'phone_number']);
        $lastSource = $this->groupVisitsQuery($group)
            ->orderByDesc('check_out_at')
            ->value('billing_source') ?? BillingSource::FREE->value;

        return [
            'client_type' => $group->user_id !== null ? 'USER' : 'WALK_IN',
            'full_name_snapshot' => $identity?->full_name,
            'phone_number_snapshot' => $identity?->phone_number,
            'phone_number_normalized' => $this->normalizePhone($identity?->phone_number),
            'first_visit_at' => $row->first_visit_at,
            'last_visit_at' => $row->last_visit_at,
            'total_visits' => (int) $row->total_visits,
            'total_minutes' => (int) $row->total_minutes,
            'free_visits' => (int) $row->free_visits,
            'global_subscription_visits' => (int) $row->global_subscription_visits,
            'workspace_subscription_visits' => (int) $row->workspace_subscription_visits,
            'last_billing_source' => $lastSource,
        ];
    }

    private function groupVisitsQuery(object $group): Builder
    {
        return DB::table('workspace_visits')
            ->where('workspace_id', $group->workspace_id)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->when(
                $group->user_id !== null,
                fn ($query) => $query->where('user_id', $group->user_id),
                fn ($query) => $query->where('walk_in_id', $group->walk_in_id),
            );
    }

    private function normalizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $phone);

        return $normalized === '' ? null : $normalized;
    }
}
