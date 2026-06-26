<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotificationPreference;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class NotificationPreferenceController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $preferences = UserNotificationPreference::firstOrCreate(
            ['user_id' => $user->id],
            UserNotificationPreference::DEFAULTS,
        );

        return ApiResponse::ok($this->payload($preferences), 'Notification preferences loaded');
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_reminders' => ['sometimes', 'boolean'],
            'subscription_alerts' => ['sometimes', 'boolean'],
            'offers_marketing' => ['sometimes', 'boolean'],
            'workspace_updates' => ['sometimes', 'boolean'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $preferences = UserNotificationPreference::firstOrCreate(
            ['user_id' => $user->id],
            UserNotificationPreference::DEFAULTS,
        );

        $preferences->fill($validated)->save();

        return ApiResponse::ok($this->payload($preferences->refresh()), 'Notification preferences updated');
    }

    /**
     * @return array<string, bool>
     */
    private function payload(UserNotificationPreference $preferences): array
    {
        return [
            'session_reminders' => $preferences->session_reminders,
            'subscription_alerts' => $preferences->subscription_alerts,
            'offers_marketing' => $preferences->offers_marketing,
            'workspace_updates' => $preferences->workspace_updates,
        ];
    }
}
