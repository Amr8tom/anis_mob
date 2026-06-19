<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Admin\Actions\LogAdminAction;
use App\Domain\WorkspaceSettlement\Actions\CreateWorkspaceSettlementAction;
use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class AdminWorkspaceSettlementController extends Controller
{
    public function store(
        Request $request,
        Workspace $workspace,
        CreateWorkspaceSettlementAction $action,
        LogAdminAction $logger,
    ): RedirectResponse {
        $validated = $request->validate([
            'payment_method' => ['required', 'string', Rule::in(['CASH', 'BANK_TRANSFER', 'MOBILE_WALLET', 'OTHER'])],
            'amount_cents' => ['required', 'integer', 'min:0'],
            'payment_reference' => ['nullable', 'string', 'max:255', 'unique:workspace_settlements,payment_reference'],
            'note' => ['nullable', 'string', 'max:255'],
            'period_started_at' => ['required', 'date'],
            'period_ended_at' => ['required', 'date', 'after_or_equal:period_started_at', 'before:today'],
        ]);

        $settlement = $action->handle(
            workspace: $workspace,
            adminId: (string) Auth::id(),
            paymentMethod: $validated['payment_method'],
            amountCents: (int) $validated['amount_cents'],
            paymentReference: $validated['payment_reference'] ?? null,
            note: $validated['note'] ?? null,
            periodStartedAt: isset($validated['period_started_at']) ? new \DateTimeImmutable($validated['period_started_at']) : null,
            periodEndedAt: isset($validated['period_ended_at']) ? new \DateTimeImmutable($validated['period_ended_at']) : null,
        );

        $logger->handle(
            adminId: (string) Auth::id(),
            action: 'CREATE_WORKSPACE_SETTLEMENT',
            entityType: 'WorkspaceSettlement',
            entityId: $settlement->id,
            details: [
                'workspace_id' => $workspace->id,
                'amount_cents' => $settlement->amount_cents,
                'currency' => $settlement->currency,
                'total_visits' => $settlement->total_visits,
                'total_minutes' => $settlement->total_minutes,
            ],
            ipAddress: $request->ip(),
        );

        return back()->with('success', 'Workspace payment recorded for the selected period.');
    }
}
