<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkspaceClient;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WorkspaceClientController extends Controller
{
    public function index(Request $request)
    {
        $workspace = Auth::user()->ownedWorkspace;
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'plan' => ['nullable', Rule::in(['FREE', 'GLOBAL_SUBSCRIPTION', 'WORKSPACE_SUBSCRIPTION'])],
        ]);
        $search = trim((string) ($filters['search'] ?? ''));
        $from = isset($filters['from']) ? Carbon::parse($filters['from']) : null;
        // datetime-local inputs have minute precision, so include the entire selected end minute.
        $toExclusive = isset($filters['to']) ? Carbon::parse($filters['to'])->startOfMinute()->addMinute() : null;
        $plan = $filters['plan'] ?? null;

        $clientsQuery = WorkspaceClient::query()
            ->where('workspace_id', $workspace->id)
            ->when($from, fn ($query) => $query->where('last_visit_at', '>=', $from))
            ->when($toExclusive, fn ($query) => $query->where('last_visit_at', '<', $toExclusive))
            ->when($plan === 'FREE', fn ($query) => $query->where('free_visits', '>', 0))
            ->when($plan === 'GLOBAL_SUBSCRIPTION', fn ($query) => $query->where('global_subscription_visits', '>', 0))
            ->when($plan === 'WORKSPACE_SUBSCRIPTION', fn ($query) => $query->where('workspace_subscription_visits', '>', 0));

        if ($search !== '') {
            $normalizedPhone = preg_replace('/\D+/', '', $search);
            $clientsQuery->where(function ($query) use ($search, $normalizedPhone): void {
                $query->where('full_name_snapshot', 'like', "%{$search}%");
                if ($normalizedPhone !== '') {
                    $query->orWhere('phone_number_normalized', 'like', "{$normalizedPhone}%");
                }
            });
        }

        $summaryRow = (clone $clientsQuery)
            ->toBase()
            ->selectRaw('COUNT(*) as total_visitors')
            ->selectRaw('COALESCE(SUM(total_visits), 0) as total_visits')
            ->selectRaw('COALESCE(SUM(total_minutes), 0) as total_minutes')
            ->first();
        $summary = [
            'total_visitors' => (int) ($summaryRow->total_visitors ?? 0),
            'total_visits' => (int) ($summaryRow->total_visits ?? 0),
            'total_minutes' => (int) ($summaryRow->total_minutes ?? 0),
        ];
        $summary['total_revenue'] = round(($summary['total_minutes'] / 60) * $workspace->effectiveHourlyRateEgp(), 2);

        $clients = $clientsQuery->orderByDesc('last_visit_at')->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return view('workspace.clients.partials.table', compact('workspace', 'clients', 'summary'))->render();
        }

        return view('workspace.clients.index', compact('workspace', 'clients', 'summary'));
    }

    public function show(Request $request, $clientId)
    {
        $workspace = Auth::user()->ownedWorkspace;
        $type = $request->validate(['type' => ['required', Rule::in(['user', 'walk_in'])]])['type'];
        $client = $type === 'user' ? User::findOrFail($clientId) : WorkspaceWalkIn::findOrFail($clientId);

        $visitsQuery = WorkspaceVisit::where('workspace_id', $workspace->id);
        if ($type === 'user') {
            $visitsQuery->where('user_id', $client->id);
        } else {
            $visitsQuery->where('walk_in_id', $client->id);
        }

        // Verify the client has visited this workspace
        abort_unless($visitsQuery->exists(), 403);

        $visits = (clone $visitsQuery)->orderByDesc('check_in_at')->paginate(10);
        $totalVisits = (clone $visitsQuery)->count();
        $totalMinutes = (int) (clone $visitsQuery)->sum('duration_minutes');
        $firstVisit = (clone $visitsQuery)->min('check_in_at');
        $lastVisit = (clone $visitsQuery)->max('check_in_at');

        return view('workspace.clients.show', compact(
            'workspace', 'client', 'visits', 'totalVisits', 'totalMinutes', 'firstVisit', 'lastVisit', 'type'
        ));
    }
}
