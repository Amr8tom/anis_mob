<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Notifications\Actions\CreateNotificationCampaignAction;
use App\Domain\Notifications\Services\NotificationAudienceQueryBuilder;
use App\Domain\Notifications\Services\NotificationAudiencePreviewService;
use App\Domain\Notifications\Services\NotificationRateLimitService;
use App\Domain\Notifications\Services\NotificationTemplateCatalog;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspacePortal\SendWorkspaceNotificationRequest;
use App\Models\NotificationCampaign;
use App\Models\StudySession;
use App\Models\User;
use App\Models\UserNotificationPreference;
use App\Models\WorkspacePrivateSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class WorkspaceNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $campaigns = NotificationCampaign::query()
            ->where('workspace_id', $workspace->id)
            ->latest()
            ->limit(20)
            ->get();
        $templates = app(NotificationTemplateCatalog::class)->workspace($workspace);
        $selectedLocale = in_array(app()->getLocale(), ['ar', 'en', 'tr'], true)
            ? app()->getLocale()
            : 'ar';

        return view('workspace.notifications.index', compact('workspace', 'campaigns', 'templates', 'selectedLocale'));
    }

    public function searchUsers(Request $request): JsonResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $q = trim($request->input('q', ''));

        $query = User::query()
            ->select(['id', 'full_name', 'phone_number'])
            ->whereExists(function ($subquery) use ($workspace): void {
                $subquery->selectRaw('1')
                    ->from('workspace_visits')
                    ->whereColumn('workspace_visits.user_id', 'users.id')
                    ->where('workspace_visits.workspace_id', $workspace->id);
            })
            ->withCount([
                'devices as devices_count' => fn ($deviceQuery) => $deviceQuery->where('is_active', true),
            ]);

        if ($q !== '') {
            $query->where(function ($sq) use ($q) {
                $sq->where('full_name', 'LIKE', "%{$q}%")
                   ->orWhere('phone_number', 'LIKE', "%{$q}%");
            });
        }

        $users = $query->limit(25)->get();

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
                ->withCount([
                    'attendees as registered_attendees_count' => fn ($query) => $query->whereNotNull('user_id'),
                ])
                ->latest()
                ->limit(50)
                ->get();

            $result = $sessions->map(
                fn (WorkspacePrivateSession $s) => [
                    'id' => $s->id,
                    'title' => $s->title ?? __('portal.notifications.unnamed_session'),
                    'starts_at' => $s->starts_at?->toIso8601String() ?? $s->created_at->toIso8601String(),
                    'student_count' => $s->registered_attendees_count,
                ]
            );

            return response()->json($result);
        }

        return response()->json([]);
    }

    public function preview(Request $request, NotificationAudiencePreviewService $preview): JsonResponse
    {
        $workspace = Auth::user()->ownedWorkspace;

        $validated = $request->validate([
            'target_type' => ['required', 'in:user,all_visitors,selected,public_session,private_session'],
            'notification_category' => ['nullable', 'in:session_reminders,subscription_alerts,offers_marketing,workspace_updates'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['uuid'],
            'session_id' => ['nullable', 'uuid'],
        ]);

        return response()->json($preview->forWorkspace($workspace, $validated['target_type'], $validated));
    }

    public function send(
        SendWorkspaceNotificationRequest $request,
        CreateNotificationCampaignAction $createCampaign,
        NotificationAudienceQueryBuilder $audienceQuery,
        NotificationRateLimitService $rateLimits,
    ) {
        $workspace = Auth::user()->ownedWorkspace;

        if ($rateLimits->workspaceCampaignLimitReached($workspace->id)) {
            $message = __('portal.notifications.workspace_daily_limit_reached', [
                'limit' => $rateLimits->workspaceDailyLimit(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 429);
            }

            return back()->with('error', $message)->withInput();
        }

        $targetType = $request->input('target_type');
        $category = UserNotificationPreference::normalizeCategory($request->input('notification_category'));
        $targetPayload = [
            'workspace_id' => $workspace->id,
        ];

        if ($targetType === 'user' || $targetType === 'selected') {
            $requestedUserIds = collect($request->input('user_ids', []))
                ->filter()
                ->unique()
                ->values()
                ->all();

            $validUserIds = User::query()
                ->whereIn('id', $requestedUserIds)
                ->whereExists(function ($subquery) use ($workspace): void {
                    $subquery->selectRaw('1')
                        ->from('workspace_visits')
                        ->whereColumn('workspace_visits.user_id', 'users.id')
                        ->where('workspace_visits.workspace_id', $workspace->id);
                })
                ->pluck('id')
                ->all();

            if ($validUserIds === []) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => __('portal.notifications.no_recipients')], 422);
                }

                return back()->with('error', __('portal.notifications.no_recipients'))->withInput();
            }

            $targetPayload['user_ids'] = $validUserIds;
        } elseif ($targetType === 'all_visitors') {
            // Resolved later by the queued audience query; do not load visitor IDs here.
        } elseif ($targetType === 'public_session') {
            $sessionId = $request->input('session_id');
            $session = $workspace->sessions()->find($sessionId);

            if (! $session) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => __('portal.notifications.no_recipients')], 422);
                }

                return back()->with('error', __('portal.notifications.no_recipients'))->withInput();
            }

            $targetPayload['session_id'] = $session->id;
        } elseif ($targetType === 'private_session') {
            $sessionId = $request->input('session_id');
            $session = WorkspacePrivateSession::where('workspace_id', $workspace->id)->find($sessionId);

            if (! $session) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => __('portal.notifications.no_recipients')], 422);
                }

                return back()->with('error', __('portal.notifications.no_recipients'))->withInput();
            }

            $targetPayload['session_id'] = $session->id;
        }

        $targetPayload['notification_data'] = array_filter([
            'type' => $category,
            'deep_link_type' => match ($targetType) {
                'public_session' => 'public_session',
                'private_session' => 'private_session',
                default => 'workspace_details',
            },
            'workspace_id' => $workspace->id,
            'session_id' => $targetPayload['session_id'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        $candidateCampaign = new NotificationCampaign([
            'workspace_id' => $workspace->id,
            'target_type' => $targetType,
            'notification_category' => $category,
            'target_payload' => $targetPayload,
        ]);

        if ($audienceQuery->deviceTokensForCampaign($candidateCampaign)->doesntExist()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => __('portal.notifications.no_recipients')], 422);
            }

            return back()->with('error', __('portal.notifications.no_recipients'))->withInput();
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store("notifications/{$workspace->id}", 'public');
            $imageUrl = Storage::disk('public')->url($path);
        }

        $campaign = $createCampaign->execute([
            'workspace_id' => $workspace->id,
            'sender_type' => 'workspace_owner',
            'sender_id' => Auth::id(),
            'target_type' => $targetType,
            'notification_category' => $category,
            'target_payload' => $targetPayload,
            'locale' => $request->input('locale', app()->getLocale()),
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image_url' => $imageUrl,
            'scheduled_at' => $request->input('scheduled_at'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'campaign_id' => $campaign->id,
                'message' => __('portal.notifications.queued'),
            ]);
        }

        return back()->with('success', __('portal.notifications.queued'));
    }
}
