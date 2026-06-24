<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class AdminNotificationController extends Controller
{
    public function index(): View
    {
        // System-wide audience = every app user that has at least one registered device.
        $audienceCount = User::whereHas('devices')->count();

        $workspaces = Workspace::orderBy('name')->get(['id', 'name']);

        return view('admin.notifications.index', compact('audienceCount', 'workspaces'));
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:2048'],
            'target_type' => ['required', 'in:all_users,workspace_visitors'],
            'workspace_id' => ['required_if:target_type,workspace_visitors', 'uuid', 'exists:workspaces,id'],
        ]);

        // Base query of recipients (only users that have a registered device).
        $recipients = User::query()->whereHas('devices');

        if ($validated['target_type'] === 'workspace_visitors') {
            // Registered visitors of this workspace only — walk-ins are excluded
            // because they have no user account.
            $visitorIds = WorkspaceVisit::where('workspace_id', $validated['workspace_id'])
                ->whereNotNull('user_id')
                ->distinct()
                ->pluck('user_id');

            $recipients->whereIn('id', $visitorIds);
        }

        $total = (clone $recipients)->count();

        if ($total === 0) {
            return back()->with('error', 'لا يوجد مستخدمون لديهم أجهزة مسجّلة لاستقبال الإشعار.')->withInput();
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            // Stored on the public disk; the URL must be publicly reachable (https) in production.
            $path = $request->file('image')->store('notifications/admin', 'public');
            $imageUrl = Storage::disk('public')->url($path);
        }

        $notification = new AdminBroadcastNotification(
            $validated['title'],
            $validated['body'],
            $imageUrl,
        );

        // Chunk so we never load the whole audience into memory. We use sendNow() so the
        // push is delivered immediately even when no queue worker is running on the host
        // (the broadcast notification is ShouldQueue, which would otherwise just sit in the
        // `jobs` table until a worker processes it).
        try {
            $recipients->chunkById(500, function ($users) use ($notification): void {
                Notification::sendNow($users, $notification);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'فشل إرسال الإشعار: '.$e->getMessage())->withInput();
        }

        return back()->with('success', "تم إرسال الإشعار إلى {$total} مستخدم.");
    }
}
