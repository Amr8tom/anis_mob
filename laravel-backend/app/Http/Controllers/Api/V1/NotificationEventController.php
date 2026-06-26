<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class NotificationEventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event' => ['required', 'string', 'in:open,click'],
            'campaign_id' => ['required', 'uuid', 'exists:notification_campaigns,id'],
            'recipient_id' => ['nullable', 'uuid'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $recipient = $this->recipientForUser(
            $user,
            (string) $validated['campaign_id'],
            $validated['recipient_id'] ?? null,
        );

        if (! $recipient instanceof NotificationRecipient) {
            return ApiResponse::ok(['tracked' => false], 'Notification event ignored');
        }

        if ($validated['event'] === 'click') {
            if ($recipient->opened_at === null) {
                $this->recordUniqueEvent($recipient, 'opened_at', 'open_count', 'opened_count');
            }

            $this->recordUniqueEvent($recipient, 'clicked_at', 'click_count', 'clicked_count');
        } else {
            $this->recordUniqueEvent($recipient, 'opened_at', 'open_count', 'opened_count');
        }

        return ApiResponse::ok(['tracked' => true], 'Notification event tracked');
    }

    private function recipientForUser(User $user, string $campaignId, ?string $recipientId): ?NotificationRecipient
    {
        $query = NotificationRecipient::query()
            ->where('campaign_id', $campaignId)
            ->where('user_id', $user->id);

        if (is_string($recipientId) && $recipientId !== '') {
            $query->whereKey($recipientId);
        }

        return $query->latest()->first();
    }

    private function recordUniqueEvent(
        NotificationRecipient $recipient,
        string $timestampColumn,
        string $recipientCountColumn,
        string $campaignCountColumn,
    ): void {
        DB::transaction(function () use ($recipient, $timestampColumn, $recipientCountColumn, $campaignCountColumn): void {
            $now = now();

            $uniqueUpdate = NotificationRecipient::query()
                ->whereKey($recipient->id)
                ->whereNull($timestampColumn)
                ->update([
                    $timestampColumn => $now,
                    'updated_at' => $now,
                ]);

            NotificationRecipient::query()
                ->whereKey($recipient->id)
                ->increment($recipientCountColumn, 1, ['updated_at' => $now]);

            if ($uniqueUpdate > 0) {
                NotificationCampaign::query()
                    ->whereKey($recipient->campaign_id)
                    ->increment($campaignCountColumn, 1, ['updated_at' => $now]);
            }
        });
    }
}
