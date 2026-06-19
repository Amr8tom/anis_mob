<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceClient\Actions;

use App\Enums\BillingSource;
use App\Enums\VisitStatus;
use App\Models\WorkspaceClient;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;

final readonly class UpsertWorkspaceClientFromVisitAction
{
    public function handle(string|WorkspaceVisit $visit): bool
    {
        $visitId = $visit instanceof WorkspaceVisit ? $visit->id : $visit;

        return DB::transaction(function () use ($visitId): bool {
            $visit = WorkspaceVisit::query()
                ->with(['user', 'walkIn'])
                ->whereKey($visitId)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $visit->status !== VisitStatus::CHECKED_OUT
                || $visit->workspace_client_counted_at !== null
                || ($visit->user_id === null && $visit->walk_in_id === null)
            ) {
                return false;
            }

            $client = $this->clientForVisit($visit);
            $source = $visit->billing_source ?? BillingSource::FREE;
            $counter = match ($source) {
                BillingSource::GLOBAL_SUBSCRIPTION => 'global_subscription_visits',
                BillingSource::WORKSPACE_SUBSCRIPTION => 'workspace_subscription_visits',
                default => 'free_visits',
            };
            $minutes = (int) ($visit->duration_minutes ?? $visit->billable_minutes ?? 0);
            $visitedAt = $visit->check_out_at ?? $visit->check_in_at;

            $client->fill([
                'client_type' => $visit->user_id !== null ? 'USER' : 'WALK_IN',
                'full_name_snapshot' => $visit->user?->full_name ?? $visit->walkIn?->full_name,
                'phone_number_snapshot' => $visit->user?->phone_number ?? $visit->walkIn?->phone_number,
                'phone_number_normalized' => $this->normalizePhone($visit->user?->phone_number ?? $visit->walkIn?->phone_number),
                'first_visit_at' => $client->first_visit_at === null || $visit->check_in_at?->lt($client->first_visit_at)
                    ? $visit->check_in_at
                    : $client->first_visit_at,
                'last_visit_at' => $client->last_visit_at === null || $visitedAt?->gt($client->last_visit_at)
                    ? $visitedAt
                    : $client->last_visit_at,
                'total_visits' => ((int) $client->total_visits) + 1,
                'total_minutes' => ((int) $client->total_minutes) + $minutes,
                $counter => ((int) $client->{$counter}) + 1,
                'last_billing_source' => $source->value,
            ]);
            $client->save();

            DB::table('workspace_visits')
                ->where('id', $visit->id)
                ->whereNull('workspace_client_counted_at')
                ->update(['workspace_client_counted_at' => now()]);

            return true;
        });
    }

    private function clientForVisit(WorkspaceVisit $visit): WorkspaceClient
    {
        $query = WorkspaceClient::query()
            ->where('workspace_id', $visit->workspace_id)
            ->when(
                $visit->user_id !== null,
                fn ($query) => $query->where('user_id', $visit->user_id),
                fn ($query) => $query->where('walk_in_id', $visit->walk_in_id),
            );

        $client = $query->lockForUpdate()->first();
        if ($client !== null) {
            return $client;
        }

        return new WorkspaceClient([
            'workspace_id' => $visit->workspace_id,
            'user_id' => $visit->user_id,
            'walk_in_id' => $visit->walk_in_id,
            'client_type' => $visit->user_id !== null ? 'USER' : 'WALK_IN',
        ]);
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
