<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\WorkspaceClient\Actions\UpsertWorkspaceClientFromVisitAction;
use App\Enums\VisitStatus;
use App\Models\WorkspaceVisit;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final class BackfillWorkspaceClientsCommand extends Command
{
    protected $signature = 'workspace-clients:backfill
        {--workspace= : Only backfill one workspace ID}
        {--from= : Include visits checked out at or after this date}
        {--to= : Include visits checked out before this date}
        {--limit= : Maximum visits to scan}';

    protected $description = 'Populate workspace client summary rows from completed workspace visits';

    public function handle(UpsertWorkspaceClientFromVisitAction $upsert): int
    {
        $query = WorkspaceVisit::query()
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereNotNull('check_out_at')
            ->whereNull('workspace_client_counted_at')
            ->when($this->option('workspace'), fn ($query, string $workspaceId) => $query->where('workspace_id', $workspaceId))
            ->when($this->option('from'), fn ($query, string $from) => $query->where('check_out_at', '>=', Carbon::parse($from)))
            ->when($this->option('to'), fn ($query, string $to) => $query->where('check_out_at', '<', Carbon::parse($to)))
            ->orderBy('id');

        $limit = $this->option('limit') !== null ? max(0, (int) $this->option('limit')) : null;
        $scanned = 0;
        $counted = 0;

        $query->lazyById(500)->each(function (WorkspaceVisit $visit) use ($upsert, $limit, &$scanned, &$counted): bool {
            if ($limit !== null && $scanned >= $limit) {
                return false;
            }

            $scanned++;
            if ($upsert->handle($visit->id)) {
                $counted++;
            }

            return true;
        });

        $this->info("Scanned {$scanned} visits; counted {$counted} workspace clients.");
        Log::info('workspace_clients.backfill.completed', [
            'scanned' => $scanned,
            'counted' => $counted,
            'workspace_id' => $this->option('workspace'),
            'from' => $this->option('from'),
            'to' => $this->option('to'),
            'limit' => $this->option('limit'),
        ]);

        return self::SUCCESS;
    }
}
