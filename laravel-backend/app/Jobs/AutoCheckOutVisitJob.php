<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\Attendance\Actions\CheckOutAction;
use App\Domain\Attendance\Actions\OwnerCheckOutVisitAction;
use App\Enums\VisitStatus;
use App\Models\WorkspaceVisit;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class AutoCheckOutVisitJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public int $uniqueFor = 600;

    public function __construct(public string $visitId)
    {
        $this->onQueue('attendance');
    }

    public function uniqueId(): string
    {
        return $this->visitId;
    }

    public function handle(CheckOutAction $checkOut, OwnerCheckOutVisitAction $ownerCheckOut): void
    {
        $visit = WorkspaceVisit::with('workspace')->find($this->visitId);
        if ($visit === null || $visit->status !== VisitStatus::CHECKED_IN) {
            return;
        }

        if ($visit->user_id !== null) {
            $checkOut->handle($visit->id, $visit->user_id);

            return;
        }

        $ownerCheckOut->handle($visit->workspace, $visit->id);
    }
}
