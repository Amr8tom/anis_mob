<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Attendance\Actions\CheckOutAction;
use App\Enums\VisitStatus;
use App\Models\WorkspaceVisit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class AutoCheckOutCommand extends Command
{
    protected $signature = 'visits:auto-checkout';

    protected $description = 'Auto check-out users from workspaces past close_time';

    public function handle(CheckOutAction $action): int
    {
        $visits = WorkspaceVisit::query()
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->with('workspace')
            ->get();

        $count = 0;

        foreach ($visits as $visit) {
            $closeTime = $visit->workspace->close_time;
            if ($closeTime === null) {
                continue;
            }

            $checkInDate = $visit->check_in_at->startOfDay();
            $closesAt = $checkInDate->setTimeFromTimeString($closeTime);

            if (now()->gte($closesAt)) {
                try {
                    $action->handle($visit->id, $visit->user_id);
                    $count++;
                } catch (\Throwable $e) {
                    Log::warning('auto-checkout.failed', [
                        'visit_id' => $visit->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Auto-checked-out {$count} visits.");

        return self::SUCCESS;
    }
}
