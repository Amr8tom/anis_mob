<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\VisitStatus;
use App\Jobs\AutoCheckOutVisitJob;
use App\Models\WorkspaceVisit;
use Illuminate\Console\Command;

final class AutoCheckOutCommand extends Command
{
    protected $signature = 'visits:auto-checkout';

    protected $description = 'Auto check-out users from workspaces past close_time';

    public function handle(): int
    {
        $visits = WorkspaceVisit::query()
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->with('workspace')
            ->lazyById(500);

        $count = 0;

        foreach ($visits as $visit) {
            $closeTime = $visit->workspace->close_time;
            if ($closeTime === null) {
                continue;
            }

            $checkInDate = $visit->check_in_at->copy()->startOfDay();
            $closesAt = $checkInDate->setTimeFromTimeString($closeTime);
            $opensAt = $visit->workspace->open_time;

            if ($opensAt !== null && $closeTime <= $opensAt) {
                $closesAt->addDay();
            }

            if (now()->gte($closesAt)) {
                AutoCheckOutVisitJob::dispatch($visit->id);
                $count++;
            }
        }

        $this->info("Queued {$count} visits for auto-checkout.");

        return self::SUCCESS;
    }
}
