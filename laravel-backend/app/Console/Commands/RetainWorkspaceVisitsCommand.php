<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

final class RetainWorkspaceVisitsCommand extends Command
{
    protected $signature = 'workspace-visits:retention
        {--older-than-days=365 : Archive checked-out visits older than this many days}
        {--workspace= : Only process one workspace ID}
        {--chunk=500 : Number of visits to scan per chunk}
        {--max-delete=10000 : Maximum raw rows to delete in this run}
        {--archive-only : Archive eligible rows but keep raw rows}
        {--before-date= : Archive visits checked out before this date instead of using older-than-days}
        {--dry-run : Show what would happen without writing}
        {--delete-after-archive=1 : Delete raw rows after archive succeeds}';

    protected $description = 'Archive and optionally delete old raw workspace visits after rollups have been preserved';

    public function handle(): int
    {
        $cutoff = $this->option('before-date') !== null
            ? Carbon::parse((string) $this->option('before-date'))->startOfDay()
            : now()->subDays(max(1, (int) $this->option('older-than-days')));
        $chunk = max(1, min(5000, (int) $this->option('chunk')));
        $maxDelete = max(0, (int) $this->option('max-delete'));
        $dryRun = (bool) $this->option('dry-run');
        $archiveOnly = (bool) $this->option('archive-only');
        $deleteAfterArchive = ! $archiveOnly && filter_var($this->option('delete-after-archive'), FILTER_VALIDATE_BOOL);

        $scanned = 0;
        $archived = 0;
        $deleted = 0;
        $skippedMissingRollup = 0;
        $skippedAccountingReference = 0;
        $skippedNotCounted = 0;
        $skippedActiveOrRecent = 0;
        $lastCheckOutAt = null;
        $lastId = null;

        while (true) {
            $visits = DB::table('workspace_visits')
                ->where('status', VisitStatus::CHECKED_OUT->value)
                ->whereNotNull('check_out_at')
                ->where('check_out_at', '<', $cutoff)
                ->when($this->option('workspace'), fn ($query, string $workspaceId) => $query->where('workspace_id', $workspaceId))
                ->when($lastCheckOutAt !== null && $lastId !== null, function ($query) use ($lastCheckOutAt, $lastId): void {
                    $query->where(function ($query) use ($lastCheckOutAt, $lastId): void {
                        $query->where('check_out_at', '>', $lastCheckOutAt)
                            ->orWhere(function ($query) use ($lastCheckOutAt, $lastId): void {
                                $query->where('check_out_at', $lastCheckOutAt)
                                    ->where('id', '>', $lastId);
                            });
                    });
                })
                ->orderBy('check_out_at')
                ->orderBy('id')
                ->limit($chunk)
                ->get();

            if ($visits->isEmpty()) {
                break;
            }

            foreach ($visits as $visit) {
                $scanned++;
                $lastCheckOutAt = $visit->check_out_at;
                $lastId = $visit->id;

                if ($visit->workspace_client_counted_at === null) {
                    $skippedNotCounted++;

                    continue;
                }

                if (! $this->hasDailyRollup($visit)) {
                    $skippedMissingRollup++;

                    continue;
                }

                if ($this->hasBlockingReferences($visit->id)) {
                    $skippedAccountingReference++;

                    continue;
                }

                if ($dryRun) {
                    $archived++;
                    if ($deleteAfterArchive && $deleted < $maxDelete) {
                        $deleted++;
                    }

                    continue;
                }

                DB::transaction(function () use ($visit, $deleteAfterArchive, $maxDelete, &$archived, &$deleted): void {
                    $inserted = DB::table('workspace_visit_archives')->insertOrIgnore([
                        'id' => $visit->id,
                        'workspace_id' => $visit->workspace_id,
                        'user_id' => $visit->user_id,
                        'walk_in_id' => $visit->walk_in_id,
                        'subscription_id' => $visit->subscription_id,
                        'workspace_subscription_id' => $visit->workspace_subscription_id ?? null,
                        'billing_source' => $visit->billing_source ?? BillingSource::FREE->value,
                        'status' => $visit->status,
                        'check_in_at' => $visit->check_in_at,
                        'check_out_at' => $visit->check_out_at,
                        'duration_minutes' => $visit->duration_minutes,
                        'billable_minutes' => $visit->billable_minutes ?? null,
                        'deducted_minutes' => $visit->deducted_minutes ?? null,
                        'hour_multiplier_applied' => $visit->hour_multiplier_applied ?? null,
                        'archived_at' => now(),
                        'original_created_at' => $visit->created_at,
                        'original_updated_at' => $visit->updated_at,
                    ]);

                    if ($inserted > 0) {
                        $archived++;
                    }

                    if ($deleteAfterArchive && $deleted < $maxDelete) {
                        $deleted += DB::table('workspace_visits')
                            ->where('id', $visit->id)
                            ->delete();
                    }
                });

                if ($deleteAfterArchive && $deleted >= $maxDelete) {
                    break 2;
                }
            }

            if ($dryRun) {
                break;
            }

            if ($deleteAfterArchive && $deleted >= $maxDelete) {
                break;
            }
        }

        $skipped = $skippedMissingRollup + $skippedAccountingReference + $skippedNotCounted + $skippedActiveOrRecent;
        $this->info("Scanned {$scanned}; archived {$archived}; deleted {$deleted}; skipped {$skipped}.");
        $this->line("skipped_missing_rollup={$skippedMissingRollup}");
        $this->line("skipped_accounting_reference={$skippedAccountingReference}");
        $this->line("skipped_not_counted={$skippedNotCounted}");
        $this->line("skipped_active_or_recent={$skippedActiveOrRecent}");
        Log::info('workspace_visits.retention.completed', [
            'cutoff' => $cutoff->toDateTimeString(),
            'dry_run' => $dryRun,
            'archive_only' => $archiveOnly,
            'scanned' => $scanned,
            'archived' => $archived,
            'deleted' => $deleted,
            'skipped_missing_rollup' => $skippedMissingRollup,
            'skipped_accounting_reference' => $skippedAccountingReference,
            'skipped_not_counted' => $skippedNotCounted,
            'skipped_active_or_recent' => $skippedActiveOrRecent,
        ]);

        return self::SUCCESS;
    }

    private function hasDailyRollup(object $visit): bool
    {
        $date = Carbon::parse($visit->check_out_at)->toDateString();
        $source = $visit->billing_source ?? BillingSource::FREE->value;

        return DB::table('workspace_daily_visit_stats')
            ->where('workspace_id', $visit->workspace_id)
            ->whereDate('stat_date', $date)
            ->where('billing_source', $source)
            ->exists();
    }

    private function hasBlockingReferences(string $visitId): bool
    {
        foreach ([
            'accounting_corrections' => 'workspace_visit_id',
            'subscription_refunds' => 'workspace_visit_id',
            'workspace_earnings' => 'workspace_visit_id',
        ] as $table => $column) {
            if (Schema::hasTable($table) && DB::table($table)->where($column, $visitId)->exists()) {
                return true;
            }
        }

        return false;
    }
}
