<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Admin\Actions\LogAdminAction;
use App\Domain\Subscription\Actions\CreateSubscriptionRefundAction;
use App\Domain\WorkspaceSettlement\Actions\CreateAccountingCorrectionAction;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\WorkspaceSettlement;
use App\Models\WorkspaceSettlementReversal;
use App\Models\WorkspaceVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AdminAccountingController extends Controller
{
    public function correctVisit(Request $request, WorkspaceVisit $visit, CreateAccountingCorrectionAction $action, LogAdminAction $logger): RedirectResponse
    {
        $validated = $request->validate([
            'minutes_delta' => ['required', 'integer', 'not_in:0'],
            'reason' => ['required', 'string', 'max:255'],
        ]);
        $correction = $action->handle($visit, (string) Auth::id(), (int) $validated['minutes_delta'], $validated['reason']);
        $logger->handle((string) Auth::id(), 'CREATE_ACCOUNTING_CORRECTION', 'AccountingCorrection', $correction->id, $validated, $request->ip());

        return back()->with('success', 'Accounting correction recorded.');
    }

    public function reverseSettlement(Request $request, WorkspaceSettlement $settlement, LogAdminAction $logger): RedirectResponse
    {
        $validated = $request->validate(['reason' => ['required', 'string', 'max:255']]);
        $reversal = WorkspaceSettlementReversal::create([
            'workspace_settlement_id' => $settlement->id,
            'created_by_admin_id' => (string) Auth::id(),
            'reason' => $validated['reason'],
        ]);
        $logger->handle((string) Auth::id(), 'REVERSE_WORKSPACE_SETTLEMENT', 'WorkspaceSettlementReversal', $reversal->id, $validated, $request->ip());

        return back()->with('success', 'Settlement reversal recorded.');
    }

    public function refundSubscription(Request $request, Subscription $subscription, CreateSubscriptionRefundAction $action, LogAdminAction $logger): RedirectResponse
    {
        $validated = $request->validate([
            'refunded_minutes' => ['required', 'integer', 'min:1'],
            'visit_id' => ['nullable', 'exists:workspace_visits,id'],
            'reason' => ['required', 'string', 'max:255'],
        ]);
        $visit = isset($validated['visit_id']) ? WorkspaceVisit::findOrFail($validated['visit_id']) : null;
        $refund = $action->handle($subscription, $visit, (string) Auth::id(), (int) $validated['refunded_minutes'], $validated['reason']);
        $logger->handle((string) Auth::id(), 'CREATE_SUBSCRIPTION_REFUND', 'SubscriptionRefund', $refund->id, $validated, $request->ip());

        return back()->with('success', 'Subscription refund recorded.');
    }
}
