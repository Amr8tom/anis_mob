<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Notifications\Actions\CreateNotificationCampaignAction;
use App\Domain\Notifications\Jobs\RetryFailedNotificationRecipientsJob;
use App\Domain\Notifications\Services\NotificationAudienceQueryBuilder;
use App\Domain\Notifications\Services\NotificationAudiencePreviewService;
use App\Domain\Notifications\Services\NotificationTemplateCatalog;
use App\Http\Controllers\Controller;
use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use App\Models\UserNotificationPreference;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class AdminNotificationController extends Controller
{
    public function index(): View
    {
        // System-wide audience = every app user that has at least one registered device.
        $audienceCount = User::whereHas(
            'devices',
            fn ($query) => $query->where('is_active', true)
        )->count();

        $workspaces = Workspace::orderBy('name')->get(['id', 'name']);
        $campaigns = NotificationCampaign::query()
            ->where('sender_type', 'admin')
            ->latest()
            ->limit(20)
            ->get();
        $templates = app(NotificationTemplateCatalog::class)->workspace();

        return view('admin.notifications.index', compact('audienceCount', 'workspaces', 'campaigns', 'templates'));
    }

    public function preview(Request $request, NotificationAudiencePreviewService $preview): JsonResponse
    {
        $validated = $request->validate([
            'target_type' => ['required', 'in:all_users,workspace_visitors'],
            'workspace_id' => ['nullable', 'uuid', 'exists:workspaces,id'],
            'notification_category' => ['nullable', 'in:session_reminders,subscription_alerts,offers_marketing,workspace_updates'],
        ]);

        return response()->json($preview->forAdmin(
            $validated['target_type'],
            $validated['workspace_id'] ?? null,
            $validated['notification_category'] ?? null,
        ));
    }

    public function send(
        Request $request,
        CreateNotificationCampaignAction $createCampaign,
        NotificationAudienceQueryBuilder $audienceQuery,
    ): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:2048'],
            'locale' => ['nullable', 'in:ar,en,tr'],
            'scheduled_at' => ['nullable', 'date'],
            'notification_category' => ['nullable', 'in:session_reminders,subscription_alerts,offers_marketing,workspace_updates'],
            'target_type' => ['required', 'in:all_users,workspace_visitors'],
            'workspace_id' => ['required_if:target_type,workspace_visitors', 'uuid', 'exists:workspaces,id'],
        ]);
        $category = UserNotificationPreference::normalizeCategory($validated['notification_category'] ?? null);

        $candidateCampaign = new NotificationCampaign([
            'workspace_id' => $validated['target_type'] === 'workspace_visitors' ? $validated['workspace_id'] : null,
            'target_type' => $validated['target_type'],
            'notification_category' => $category,
            'target_payload' => ['workspace_id' => $validated['workspace_id'] ?? null],
        ]);

        if ($audienceQuery->deviceTokensForCampaign($candidateCampaign)->doesntExist()) {
            return back()->with('error', 'لا يوجد مستخدمون لديهم أجهزة مسجّلة لاستقبال الإشعار.')->withInput();
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            // Stored on the public disk; the URL must be publicly reachable (https) in production.
            $path = $request->file('image')->store('notifications/admin', 'public');
            $imageUrl = Storage::disk('public')->url($path);
        }

        $createCampaign->execute([
            'sender_type' => 'admin',
            'sender_id' => auth('admin')->id() ?? auth()->id(),
            'workspace_id' => $validated['target_type'] === 'workspace_visitors' ? $validated['workspace_id'] : null,
            'target_type' => $validated['target_type'],
            'notification_category' => $category,
            'target_payload' => [
                'workspace_id' => $validated['workspace_id'] ?? null,
                'notification_data' => array_filter([
                    'type' => 'admin_broadcast',
                    'deep_link_type' => isset($validated['workspace_id']) ? 'workspace_details' : 'home',
                    'workspace_id' => $validated['workspace_id'] ?? null,
                    'notification_category' => $category,
                ], fn ($value) => $value !== null && $value !== ''),
            ],
            'locale' => $validated['locale'] ?? 'ar',
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image_url' => $imageUrl,
            'scheduled_at' => $validated['scheduled_at'] ?? null,
        ]);

        return back()->with('success', 'تم وضع الإشعار في قائمة الإرسال. يمكنك متابعة حالة الحملة من سجل الإشعارات.');
    }

    public function show(NotificationCampaign $campaign): View
    {
        abort_unless($campaign->sender_type === 'admin', 404);

        $campaign->load('workspace:id,name');
        $recipients = $campaign->recipients()
            ->with([
                'user:id,full_name,phone_number',
                'deviceToken:id,token,platform,is_active',
            ])
            ->latest()
            ->paginate(50);

        $recipientStats = $campaign->recipients()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.notifications.show', compact('campaign', 'recipients', 'recipientStats'));
    }

    public function retryFailed(NotificationCampaign $campaign): RedirectResponse
    {
        abort_unless($campaign->sender_type === 'admin', 404);

        $retryableCount = NotificationRecipient::query()
            ->where('campaign_id', $campaign->id)
            ->where('status', NotificationRecipient::STATUS_FAILED)
            ->whereNotNull('device_token_id')
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('device_tokens')
                    ->whereColumn('device_tokens.id', 'notification_recipients.device_token_id')
                    ->where('device_tokens.is_active', true);
            })
            ->count();

        if ($retryableCount === 0) {
            return back()->with('error', 'لا توجد محاولات فاشلة قابلة للإعادة حاليًا.');
        }

        RetryFailedNotificationRecipientsJob::dispatch($campaign->id)
            ->onQueue((string) config('notification_campaigns.queue', 'notifications'));

        return back()->with('success', "تم وضع {$retryableCount} مستلم فاشل في قائمة إعادة الإرسال.");
    }
}
