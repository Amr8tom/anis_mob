<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications;

use App\Models\NotificationCampaign;
use App\Models\ScheduledNotificationTask;
use App\Models\StudySession;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Models\WorkspaceSubscription;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class ScheduledNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['notification_campaigns.quiet_hours.enabled' => false]);
    }

    public function test_processes_public_session_reminder_before_18_hours(): void
    {
        Notification::fake();

        $workspace = Workspace::factory()->create(['notification_locale' => 'en']);
        $user = User::factory()->create();
        $user->devices()->create(['token' => 'public-session-token', 'platform' => 'ios']);
        $session = StudySession::factory()->create([
            'workspace_id' => $workspace->id,
            'start_time' => now()->addHours(17)->addMinutes(55),
            'title' => 'Physics Review',
        ]);
        $session->participants()->attach($user->id, ['joined_at' => now()]);

        $this->artisan('notifications:process-scheduled')->assertSuccessful();

        $task = ScheduledNotificationTask::where('type', ScheduledNotificationTask::TYPE_PUBLIC_SESSION_18H)->firstOrFail();
        $campaign = NotificationCampaign::findOrFail($task->campaign_id);

        $this->assertSame(ScheduledNotificationTask::STATUS_PROCESSED, $task->status);
        $this->assertSame('public_session', $campaign->target_type);
        $this->assertSame('en', $campaign->locale);
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
        $this->assertSame(1, $campaign->sent_count);
    }

    public function test_processes_private_session_reminder_before_18_hours(): void
    {
        Notification::fake();

        $workspace = Workspace::factory()->create();
        $user = User::factory()->create();
        $user->devices()->create(['token' => 'private-session-token', 'platform' => 'android']);
        $session = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $workspace->id,
            'starts_at' => now()->addHours(17)->addMinutes(55),
            'title' => 'Arabic Class',
            'status' => 'active',
        ]);
        WorkspacePrivateSessionAttendee::factory()->create([
            'workspace_private_session_id' => $session->id,
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'status' => 'invited',
        ]);

        $this->artisan('notifications:process-scheduled')->assertSuccessful();

        $task = ScheduledNotificationTask::where('type', ScheduledNotificationTask::TYPE_PRIVATE_SESSION_18H)->firstOrFail();
        $campaign = NotificationCampaign::findOrFail($task->campaign_id);

        $this->assertSame(ScheduledNotificationTask::STATUS_PROCESSED, $task->status);
        $this->assertSame('private_session', $campaign->target_type);
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
        $this->assertSame(1, $campaign->sent_count);
    }

    public function test_processes_workspace_subscription_expiry_two_day_reminder(): void
    {
        Notification::fake();

        $workspace = Workspace::factory()->create();
        $user = User::factory()->create();
        $user->devices()->create(['token' => 'expiry-token', 'platform' => 'ios']);
        $subscription = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'expires_at' => now()->addHours(47),
            'remaining_minutes' => 20 * 60,
            'status' => 'ACTIVE',
        ]);

        $this->artisan('notifications:process-scheduled')->assertSuccessful();

        $task = ScheduledNotificationTask::where('type', ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D)->firstOrFail();
        $campaign = NotificationCampaign::findOrFail($task->campaign_id);

        $this->assertSame($subscription->id, $task->entity_id);
        $this->assertSame(ScheduledNotificationTask::STATUS_PROCESSED, $task->status);
        $this->assertSame('workspace_subscription', $campaign->target_type);
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
        $this->assertSame(1, $campaign->sent_count);
    }

    public function test_processes_workspace_subscription_low_hours_reminder(): void
    {
        Notification::fake();

        $workspace = Workspace::factory()->create();
        $user = User::factory()->create();
        $user->devices()->create(['token' => 'low-hours-token', 'platform' => 'android']);
        $subscription = WorkspaceSubscription::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'remaining_minutes' => (16 * 60) - 1,
            'status' => 'ACTIVE',
        ]);

        $this->artisan('notifications:process-scheduled')->assertSuccessful();

        $task = ScheduledNotificationTask::where('type', ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H)->firstOrFail();
        $campaign = NotificationCampaign::findOrFail($task->campaign_id);

        $this->assertSame($subscription->id, $task->entity_id);
        $this->assertSame(ScheduledNotificationTask::STATUS_PROCESSED, $task->status);
        $this->assertSame('workspace_subscription', $campaign->target_type);
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
        $this->assertSame(1, $campaign->sent_count);
    }

    public function test_prunes_recipient_details_after_maximum_retention_window(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-06-25 12:00:00'));

        $campaign = NotificationCampaign::create([
            'sender_type' => 'admin',
            'target_type' => 'all_users',
            'notification_category' => 'workspace_updates',
            'target_payload' => [],
            'locale' => 'ar',
            'title' => 'Old Campaign',
            'body' => 'Old Body',
            'status' => NotificationCampaign::STATUS_SENT,
            'targeted_count' => 1,
            'sent_count' => 1,
            'created_at' => now()->subDays(61),
            'updated_at' => now()->subDays(61),
        ]);

        $user = User::factory()->create();
        $device = $user->devices()->create(['token' => 'old-token', 'platform' => 'android']);
        $campaign->recipients()->create([
            'user_id' => $user->id,
            'device_token_id' => $device->id,
            'locale' => 'ar',
            'status' => 'sent',
            'sent_at' => now()->subDays(61),
            'created_at' => now()->subDays(61),
            'updated_at' => now()->subDays(61),
        ]);

        $this->artisan('notifications:prune-recipients', ['--days' => 90])->assertSuccessful();

        $this->assertDatabaseMissing('notification_recipients', [
            'campaign_id' => $campaign->id,
        ]);

        $campaign->refresh();
        $this->assertSame(1, $campaign->targeted_count);
        $this->assertSame(1, $campaign->sent_count);
        $this->assertNotNull($campaign->recipients_pruned_at);

        Carbon::setTestNow();
    }
}
