<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\WorkspaceVisit;
use Illuminate\Support\Facades\DB;

class WorkspaceClientController extends Controller
{
    public function index(Request $request)
    {
        $workspace = Auth::user()->ownedWorkspace;

        // Get unique visitors for this workspace, counting their visits and getting last visit time
        $clientsQuery = User::select(
            'users.id',
            'users.full_name',
            'users.phone_number',
            DB::raw('COUNT(workspace_visits.id) as total_visits'),
            DB::raw('MAX(workspace_visits.check_in_at) as last_visit')
        )
        ->join('workspace_visits', 'users.id', '=', 'workspace_visits.user_id')
        ->where('workspace_visits.workspace_id', $workspace->id)
        ->groupBy('users.id', 'users.full_name', 'users.phone_number');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $clientsQuery->where(function($q) use ($search) {
                $q->where('users.full_name', 'like', "%{$search}%")
                  ->orWhere('users.phone_number', 'like', "%{$search}%");
            });
        }

        $clients = $clientsQuery->orderByDesc('last_visit')->paginate(15);

        return view('workspace.clients.index', compact('workspace', 'clients'));
    }

    public function show($clientId)
    {
        $workspace = Auth::user()->ownedWorkspace;

        $client = User::findOrFail($clientId);

        $visits = WorkspaceVisit::where('workspace_id', $workspace->id)
            ->where('user_id', $client->id)
            ->orderByDesc('check_in_at')
            ->paginate(10);

        return view('workspace.clients.show', compact('workspace', 'client', 'visits'));
    }
}
