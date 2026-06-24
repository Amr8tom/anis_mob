<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspacePortal\SendWorkspaceNotificationRequest;
use App\Models\StudySession;
use App\Models\User;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Models\WorkspaceVisit;
use App\Notifications\WorkspaceBroadcastNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class WorkspaceNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $workspace = Auth::user()->ownedWorkspace;

        return view('workspace.notifications.index', compact('workspace'));
    }

    public function searchUsers(Request $request): JsonResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $q = trim($request->input('q', ''));

        // Base user IDs (must be registered visitors of THIS workspace)
        $baseUserIds = WorkspaceVisit::where('workspace_id', $workspace->id)
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        if (empty($baseUserIds)) {
            return response()->json([]);
        }

        $query = User::whereIn('id', $baseUserIds)
            ->withCount('devices');

        if ($q !== '') {
            $query->where(function ($sq) use ($q) {
                $sq->where('full_name', 'LIKE', "%{$q}%")
                   ->orWhere('phone_number', 'LIKE', "%{$q}%");
            });
        }

        $users = $query->limit(25)->get(['id', 'full_name', 'phone_number', 'devices_count']);

        // Format for frontend
        $result = $users->map(fn (User $u) => [
            'id' => $u->id,
            'name' => $u->full_name,
            'phone_number' => $u->phone_number,
            'device_count' => $u->devices_count,
        ]);

        return response()->json($result);
    }

    public function sessions(Request $request): JsonResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $type = $request->input('type');

        if ($type === 'public') {
            $sessions = $workspace->sessions()
                ->withCount('participants')
                ->latest()
                ->limit(50)
                ->get();

            $result = $sessions->map(fn (StudySession $s) => [
                'id' => $s->id,
                'title' => $s->title ?? __('portal.notifications.unnamed_session'),
                'starts_at' => $s->start_time?->toIso8601String(),
                'student_count' => $s->participants_count,
            ]);

            return response()->json($result);
        }

        if ($type === 'private') {
            $sessions = WorkspacePrivateSession::where('workspace_id', $workspace->id)
                ->latest()
                ->limit(50)
                ->get();

            // Private sessions don't have a direct count relation, we can aggregate
            $result = $sessions->map(function (WorkspacePrivateSession $s) {
                $count = WorkspacePrivateSessionAttendee::where('workspace_private_session_id', $s->id)
                    ->whereNotNull('user_id')
                    ->count();

                return [
                    'id' => $s->id,
                    'title' => $s->title ?? __('portal.notifications.unnamed_session'),
                    'starts_at' => $s->starts_at?->toIso8601String() ?? $s->created_at->toIso8601String(),
                    'student_count' => $count,
                ];
            });

            return response()->json($result);
        }

        return response()->json([]);
    }

    public function send(SendWorkspaceNotificationRequest $request)
    {
        $workspace = Auth::user()->ownedWorkspace;
        $targetType = $request->input('target_type');
        $userIds = [];

        // 1. Resolve raw target user IDs based on target_type
        if ($targetType === 'user' || $targetType === 'selected') {
            $userIds = $request->input('user_ids', []);
        } elseif ($targetType === 'all_visitors') {
            $userIds = WorkspaceVisit::where('workspace_id', $workspace->id)
                ->whereNotNull('user_id')
                ->distinct()
                ->pluck('user_id')
                ->toArray();
        } elseif ($targetType === 'public_session') {
            $sessionId = $request->input('session_id');
            $session = $workspace->sessions()->find($sessionId);
            if ($session) {
                $userIds = $session->participants()->pluck('users.id')->toArray();
            }
        } elseif ($targetType === 'private_session') {
            $sessionId = $request->input('session_id');
            $session = WorkspacePrivateSession::where('workspace_id', $workspace->id)->find($sessionId);
            if ($session) {
                $userIds = WorkspacePrivateSessionAttendee::where('workspace_private_session_id', $session->id)
                    ->whereNotNull('user_id')
                    ->distinct()
                    ->pluck('user_id')
                    ->toArray();
            }
        }

        // 2. Intersect with actual workspace registered visitors to prevent abuse
        $workspaceVisitorIds = WorkspaceVisit::where('workspace_id', $workspace->id)
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id')
            ->toArray();

        $validUserIds = array_intersect($userIds, $workspaceVisitorIds);

        if (empty($validUserIds)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => __('portal.notifications.no_recipients')], 422);
            }
            return back()->with('error', __('portal.notifications.no_recipients'))->withInput();
        }

        // 3. Handle Image Upload
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("notifications/{$workspace->id}", 'public');
            $imageUrl = Storage::disk('public')->url($path);
        }

        // 4. Fetch users who actually have devices
        $users = User::whereIn('id', $validUserIds)
            ->whereHas('devices')
            ->get();

        if ($users->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => __('portal.notifications.no_recipients_with_devices')], 422);
            }
            return back()->with('error', __('portal.notifications.no_recipients_with_devices'))->withInput();
        }

        // 5. Dispatch Notification
        $title = $request->input('title');
        $body = $request->input('body');

        // sendNow() delivers immediately even without a running queue worker (the
        // notification is ShouldQueue, so send() would only enqueue it).
        try {
            Notification::sendNow($users, new WorkspaceBroadcastNotification($title, $body, $imageUrl, $workspace->id));
        } catch (\Throwable $e) {
            report($e);

            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return back()->with('error', 'فشل إرسال الإشعار: '.$e->getMessage())->withInput();
        }

        // 6. Return response
        if ($request->expectsJson()) {
            return response()->json([
                'targeted' => count($validUserIds),
                'sent' => $users->count(),
                'message' => __('portal.notifications.sent', ['count' => $users->count()]),
            ]);
        }

        return back()->with('success', __('portal.notifications.sent', ['count' => $users->count()]));
    }
}
