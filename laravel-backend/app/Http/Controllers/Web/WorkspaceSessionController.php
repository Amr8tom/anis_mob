<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StudySession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceSessionController extends Controller
{
    public function index()
    {
        $workspace = Auth::user()->ownedWorkspace;
        $sessions = $workspace->sessions()->latest()->paginate(10);
        return view('workspace.sessions.index', compact('workspace', 'sessions'));
    }

    public function create()
    {
        $workspace = Auth::user()->ownedWorkspace;
        return view('workspace.sessions.create', compact('workspace'));
    }

    public function store(Request $request)
    {
        $workspace = Auth::user()->ownedWorkspace;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_name' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
            'price_cents' => 'required|integer|min:0',
        ]);

        $workspace->sessions()->create([
            'host_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructor_name' => $validated['instructor_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_seats' => $validated['max_participants'] ?? null,
            'price_cents' => $validated['price_cents'],
            'time_label' => \Carbon\Carbon::parse($validated['start_time'])->format('g:i A'),
        ]);

        return redirect()->route('workspace.sessions.index')->with('success', 'تم إضافة الجلسة بنجاح.');
    }

    public function edit(StudySession $session)
    {
        $workspace = Auth::user()->ownedWorkspace;

        if ($session->workspace_id !== $workspace->id) {
            abort(403);
        }

        return view('workspace.sessions.edit', compact('workspace', 'session'));
    }

    public function update(Request $request, StudySession $session)
    {
        $workspace = Auth::user()->ownedWorkspace;

        if ($session->workspace_id !== $workspace->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_name' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
            'price_cents' => 'required|integer|min:0',
        ]);

        $session->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructor_name' => $validated['instructor_name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'max_seats' => $validated['max_participants'] ?? null,
            'price_cents' => $validated['price_cents'],
            'time_label' => \Carbon\Carbon::parse($validated['start_time'])->format('g:i A'),
        ]);

        return redirect()->route('workspace.sessions.index')->with('success', 'تم تحديث الجلسة بنجاح.');
    }

    public function destroy(StudySession $session)
    {
        $workspace = Auth::user()->ownedWorkspace;

        if ($session->workspace_id !== $workspace->id) {
            abort(403);
        }

        $session->delete();

        return redirect()->route('workspace.sessions.index')->with('success', 'تم حذف الجلسة بنجاح.');
    }
}
