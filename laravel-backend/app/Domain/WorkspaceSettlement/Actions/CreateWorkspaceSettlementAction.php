<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSettlement\Actions;

use App\Models\Workspace;
use App\Models\WorkspaceSettlement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class CreateWorkspaceSettlementAction
{
    public function __construct(private GetWorkspaceVisitReportAction $report) {}

    public function handle(
        Workspace $workspace,
        string $adminId,
        string $paymentMethod,
        int $amountCents,
        ?string $paymentReference = null,
        ?string $note = null,
        ?\DateTimeInterface $periodStartedAt = null,
        ?\DateTimeInterface $periodEndedAt = null,
    ): WorkspaceSettlement {
        if ($periodStartedAt === null || $periodEndedAt === null) {
            throw ValidationException::withMessages([
                'period_started_at' => 'A payment period is required.',
            ]);
        }

        $from = Carbon::instance($periodStartedAt)->startOfDay();
        $toExclusive = Carbon::instance($periodEndedAt)->addDay()->startOfDay();

        if ($toExclusive->greaterThan(now()->startOfDay())) {
            throw ValidationException::withMessages([
                'period_ended_at' => 'Only completed periods ending before today can be marked as paid.',
            ]);
        }

        return DB::transaction(function () use ($workspace, $adminId, $paymentMethod, $amountCents, $paymentReference, $note, $from, $toExclusive) {
            Workspace::query()->whereKey($workspace->id)->lockForUpdate()->firstOrFail();

            $overlapExists = WorkspaceSettlement::query()
                ->where('workspace_id', $workspace->id)
                ->where('period_started_at', '<', $toExclusive)
                ->where('period_ended_at', '>=', $from)
                ->exists();

            if ($overlapExists) {
                throw ValidationException::withMessages([
                    'period_started_at' => 'This period overlaps a workspace period already marked as paid.',
                ]);
            }

            $summary = $this->report->handle($workspace, $from, $toExclusive);
            if ($summary['total_visits'] === 0) {
                throw ValidationException::withMessages([
                    'period_started_at' => 'No completed workspace visits were found in this period.',
                ]);
            }

            return WorkspaceSettlement::create([
                'workspace_id' => $workspace->id,
                'created_by_admin_id' => $adminId,
                ...$summary,
                'amount_cents' => $amountCents,
                'currency' => 'EGP',
                'payment_method' => $paymentMethod,
                'payment_reference' => $paymentReference,
                'note' => $note,
                'period_started_at' => $from,
                'period_ended_at' => $toExclusive->copy()->subSecond(),
                'paid_at' => now(),
            ]);
        });
    }
}
