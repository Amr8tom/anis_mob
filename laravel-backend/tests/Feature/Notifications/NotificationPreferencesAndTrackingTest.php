<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Domain\Notifications\Services\NotificationAudienceQueryBuilder;
use App\Models\DeviceToken;
use App\Models\NotificationCampaign;
use App\Models\NotificationRecipient;
use App\Models\User;
use App\Models\UserNotificationPreference;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class NotificationPreferencesAndTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_read_and_update_notification_preferences(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/notification-preferences')
            ->assertOk()
            ->assertJsonPath('data.session_reminders', true)
            ->assertJsonPath('data.subscription_alerts', true)
            ->assertJsonPath('data.offers_marketing', true)
            ->assertJsonPath('data.workspace_updates', true);

        $this->patchJson('/api/v1/notification-preferences', [
            'offers_marketing' => false,
            'workspace_updates' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.session_reminders', true)
            ->assertJsonPath('data.subscription_alerts', true)
            ->assertJsonPath('data.offers_marketing', false)
            ->assertJsonPath('data.workspace_updates', false);

        $this->assertDatabaseHas('user_notification_preferences', [
            'user_id' => $user->id,
            'offers_marketing' => false,
            'workspace_updates' => false,
        ]);
    }

    public function test_disabled_category_is_excluded_from_large_audience_queries(): void
    {
        $workspace = Workspace::factory()->create();
        $allowedUser = User::factory()->create();
        $disabledUser = User::factory()->create();

        DeviceToken::create(['user_id' => $allowedUser->id, 'token' => 'allowed-token']);
        DeviceToken::create(['user_id' => $disabledUser->id, 'token' => 'disabled-token']);

        WorkspaceVisit::factory()->create(['workspace_id' => $workspace->id, 'user_id' => $allowedUser->id]);
        WorkspaceVisit::factory()->create(['workspace_id' => $workspace->id, 'user_id' => $disabledUser->id]);

        UserNotificationPreference::create([
            'user_id' => $disabledUser->id,
            'session_reminders' => true,
            'subscription_alerts' => true,
            'offers_marketing' => false,
            'workspace_updates' => true,
        ]);

        $campaign = NotificationCampaign::create([
            'workspace_id' => $workspace->id,
            'sender_type' => 'workspace_owner',
            'target_type' => 'all_visitors',
            'notification_category' => UserNotificationPreference::CATEGORY_OFFERS_MARKETING,
            'target_payload' => ['workspace_id' => $workspace->id],
            'locale' => 'ar',
            'title' => 'Offer',
            'body' => 'Offer body',
            'status' => NotificationCampaign::STATUS_PENDING,
            'queued_at' => now(),
        ]);

        $tokens = app(NotificationAudienceQueryBuilder::class)
            ->deviceTokensForCampaign($campaign)
            ->pluck('token')
            ->all();

        $this->assertSame(['allowed-token'], $tokens);
    }

    public function test_notification_events_track_unique_opens_and_clicks(): void
    {
        $user = User::factory()->create();
        $deviceToken = DeviceToken::create(['user_id' => $user->id, 'token' => 'tracking-token']);
        $campaign = NotificationCampaign::create([
            'sender_type' => 'admin',
            'target_type' => 'all_users',
            'notification_category' => UserNotificationPreference::CATEGORY_WORKSPACE_UPDATES,
            'target_payload' => [],
            'locale' => 'ar',
            'title' => 'Hello',
            'body' => 'World',
            'status' => NotificationCampaign::STATUS_SENT,
            'queued_at' => now(),
        ]);
        $recipient = NotificationRecipient::create([
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'device_token_id' => $deviceToken->id,
            'status' => NotificationRecipient::STATUS_SENT,
            'sent_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $payload = [
            'campaign_id' => $campaign->id,
            'recipient_id' => $recipient->id,
        ];

        $this->postJson('/api/v1/notifications/events', $payload + ['event' => 'open'])
            ->assertOk()
            ->assertJsonPath('data.tracked', true);
        $this->postJson('/api/v1/notifications/events', $payload + ['event' => 'open'])
            ->assertOk()
            ->assertJsonPath('data.tracked', true);
        $this->postJson('/api/v1/notifications/events', $payload + ['event' => 'click'])
            ->assertOk()
            ->assertJsonPath('data.tracked', true);
        $this->postJson('/api/v1/notifications/events', $payload + ['event' => 'click'])
            ->assertOk()
            ->assertJsonPath('data.tracked', true);

        $recipient->refresh();
        $campaign->refresh();

        $this->assertNotNull($recipient->opened_at);
        $this->assertNotNull($recipient->clicked_at);
        $this->assertSame(2, $recipient->open_count);
        $this->assertSame(2, $recipient->click_count);
        $this->assertSame(1, $campaign->opened_count);
        $this->assertSame(1, $campaign->clicked_count);
    }

    public function test_tracking_ignores_events_for_other_users(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $campaign = NotificationCampaign::create([
            'sender_type' => 'admin',
            'target_type' => 'all_users',
            'notification_category' => UserNotificationPreference::CATEGORY_WORKSPACE_UPDATES,
            'target_payload' => [],
            'locale' => 'ar',
            'title' => 'Hello',
            'body' => 'World',
            'status' => NotificationCampaign::STATUS_SENT,
            'queued_at' => now(),
        ]);
        $recipient = NotificationRecipient::create([
            'campaign_id' => $campaign->id,
            'user_id' => $owner->id,
            'status' => NotificationRecipient::STATUS_SENT,
            'sent_at' => now(),
        ]);

        Sanctum::actingAs($otherUser);

        $this->postJson('/api/v1/notifications/events', [
            'event' => 'open',
            'campaign_id' => $campaign->id,
            'recipient_id' => $recipient->id,
        ])
            ->assertOk()
            ->assertJsonPath('data.tracked', false);

        $this->assertNull($recipient->refresh()->opened_at);
        $this->assertSame(0, $campaign->refresh()->opened_count);
    }
}
