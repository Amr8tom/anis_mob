<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\WorkspaceSettlement\Actions\GetWorkspaceVisitReportAction;
use App\Http\Controllers\Controller;
use App\Models\WorkspaceSettlement;
use Illuminate\Support\Facades\Auth;

final class WorkspaceFinancialController extends Controller
{
    public function index(GetWorkspaceVisitReportAction $report)
    {
        $workspace = Auth::user()->ownedWorkspace;
        $from = now()->startOfMonth();
        $to = now()->endOfMonth();
        $summary = $report->handle($workspace, $from, $to->copy()->addSecond());

        $settlements = WorkspaceSettlement::where('workspace_id', $workspace->id)
            ->latest('paid_at')
            ->paginate(15);

        return view('workspace.financials.index', compact('workspace', 'settlements', 'summary', 'from', 'to'));
    }
}
