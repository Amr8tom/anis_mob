<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Models\NotificationCampaign;
use App\Models\StudySession;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceOwner;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkspaceNotificationTest extends TestCase
{
    use RefreshDatabase;

    private WorkspaceOwner $owner;
    private Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        config(['notification_campaigns.quiet_hours.enabled' => false]);
        
        $this->owner = WorkspaceOwner::factory()->create();
        $this->workspace = Workspace::factory()->create(['workspace_owner_id' => $this->owner->id]);
    }

    public function test_can_view_notifications_page(): void
    {
        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->get(route('workspace.notifications.index'));

        $response->assertOk();
        $response->assertSee('target_type');
        $response->assertDontSeeText('portal.notifications.category_label');
        $response->assertDontSeeText('php $selectedLocale');
        $response->assertDontSeeText('@php');
        $response->assertDontSeeText('{{');
    }

    public function test_search_users_returns_only_registered_visitors(): void
    {
        // User 1: visited this workspace, registered
        $user1 = User::factory()->create(['full_name' => 'Alice Registered', 'phone_number' => '11111111']);
        $user1->devices()->create(['token' => 'token1', 'platform' => 'android']);
        WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $user1->id,
        ]);

        // User 2: registered, visited ANOTHER workspace
        $user2 = User::factory()->create(['full_name' => 'Charlie External', 'phone_number' => '33333333']);
        $otherWorkspace = Workspace::factory()->create();
        WorkspaceVisit::factory()->create([
            'workspace_id' => $otherWorkspace->id,
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->getJson(route('workspace.notifications.search', ['q' => '111']));

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.id', $user1->id);
    }

    public function test_sessions_endpoint_returns_active_sessions(): void
    {
        // Public session
        $publicSession = StudySession::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Public Math Class',
        ]);

        // Private session
        $privateSession = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $this->workspace->id,
            'title' => 'Private English Class',
        ]);

        // Test public
        $responsePublic = $this->actingAs($this->owner, 'workspace_owner')
            ->getJson(route('workspace.notifications.sessions', ['type' => 'public']));
        $responsePublic->assertOk();
        $responsePublic->assertJsonCount(1);
        $responsePublic->assertJsonPath('0.id', $publicSession->id);

        // Test private
        $responsePrivate = $this->actingAs($this->owner, 'workspace_owner')
            ->getJson(route('workspace.notifications.sessions', ['type' => 'private']));
        $responsePrivate->assertOk();
        $responsePrivate->assertJsonCount(1);
        $responsePrivate->assertJsonPath('0.id', $privateSession->id);
    }

    public function test_preview_all_visitors_counts_reachable_users_and_skips_walk_ins(): void
    {
        $reachableUser = User::factory()->create();
        $reachableUser->devices()->create(['token' => 'reachable-token-1', 'platform' => 'android']);
        $reachableUser->devices()->create(['token' => 'reachable-token-2', 'platform' => 'ios']);
        WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $reachableUser->id,
        ]);

        $registeredWithoutDevice = User::factory()->create();
        WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $registeredWithoutDevice->id,
        ]);

        $walkIn = WorkspaceWalkIn::create([
            'workspace_id' => $this->workspace->id,
            'full_name' => 'Walk In Visitor',
            'phone_number' => '01099998888',
        ]);
        WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => null,
            'walk_in_id' => $walkIn->id,
        ]);

        $outsideUser = User::factory()->create();
        $outsideUser->devices()->create(['token' => 'outside-token', 'platform' => 'android']);

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->postJson(route('workspace.notifications.preview'), [
                'target_type' => 'all_visitors',
            ]);

        $response->assertOk();
        $response->assertJson([
            'audience_count' => 3,
            'registered_users_count' => 2,
            'reachable_users_count' => 1,
            'device_tokens_count' => 2,
            'skipped_count' => 2,
        ]);
    }

    public function test_preview_specific_users_counts_only_selected_workspace_visitors(): void
    {
        $reachableUser = User::factory()->create();
        $reachableUser->devices()->create(['token' => 'selected-reachable-token', 'platform' => 'android']);
        WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $reachableUser->id,
        ]);

        $registeredWithoutDevice = User::factory()->create();
        WorkspaceVisit::factory()->create([
            'workspace_id' => $this->workspace->id,
            'user_id' => $registeredWithoutDevice->id,
        ]);

        $outsideUser = User::factory()->create();
        $outsideUser->devices()->create(['token' => 'selected-outside-token', 'platform' => 'android']);

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->postJson(route('workspace.notifications.preview'), [
                'target_type' => 'user',
                'user_ids' => [$reachableUser->id, $registeredWithoutDevice->id, $outsideUser->id],
            ]);

        $response->assertOk();
        $response->assertJson([
            'audience_count' => 2,
            'registered_users_count' => 2,
            'reachable_users_count' => 1,
            'device_tokens_count' => 1,
            'skipped_count' => 1,
        ]);
    }

    public function test_preview_private_session_includes_manual_attendees_but_reaches_app_users_only(): void
    {
        $privateSession = WorkspacePrivateSession::factory()->create([
            'workspace_id' => $this->workspace->id,
        ]);

        $reachableUser = User::factory()->create();
        $reachableUser->devices()->create(['token' => 'private-reachable-token', 'platform' => 'android']);
        WorkspacePrivateSessionAttendee::factory()->create([
            'workspace_id' => $this->workspace->id,
            'workspace_private_session_id' => $privateSession->id,
            'user_id' => $reachableUser->id,
        ]);

        $registeredWithoutDevice = User::factory()->create();
        WorkspacePrivateSessionAttendee::factory()->create([
            'workspace_id' => $this->workspace->id,
            'workspace_private_session_id' => $privateSession->id,
            'user_id' => $registeredWithoutDevice->id,
        ]);

        WorkspacePrivateSessionAttendee::factory()->create([
            'workspace_id' => $this->workspace->id,
            'workspace_private_session_id' => $privateSession->id,
            'user_id' => null,
            'name_snapshot' => 'Manual Attendee',
            'phone_snapshot' => '01077776666',
        ]);

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->postJson(route('workspace.notifications.preview'), [
                'target_type' => 'private_session',
                'session_id' => $privateSession->id,
            ]);

        $response->assertOk();
        $response->assertJson([
            'audience_count' => 3,
            'registered_users_count' => 2,
            'reachable_users_count' => 1,
            'device_tokens_count' => 1,
            'skipped_count' => 2,
        ]);
    }

    public function test_can_send_notification_to_specific_users(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->devices()->create(['token' => 'test-token-specific', 'platform' => 'android']);
        WorkspaceVisit::factory()->create(['workspace_id' => $this->workspace->id, 'user_id' => $user->id]);

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->post(route('workspace.notifications.send'), [
                'title' => 'Test Title',
                'body' => 'Test Body',
                'target_type' => 'user',
                'user_ids' => [$user->id],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notification_campaigns', [
            'workspace_id' => $this->workspace->id,
            'target_type' => 'user',
            'title' => 'Test Title',
            'status' => NotificationCampaign::STATUS_SENT,
            'targeted_count' => 1,
            'sent_count' => 1,
        ]);

        $this->assertDatabaseHas('notification_recipients', [
            'user_id' => $user->id,
            'status' => 'sent',
        ]);
    }

    public function test_can_schedule_workspace_notification_for_later(): void
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-06-25 12:00:00'));

        $user = User::factory()->create();
        $user->devices()->create(['token' => 'scheduled-token', 'platform' => 'android']);
        WorkspaceVisit::factory()->create(['workspace_id' => $this->workspace->id, 'user_id' => $user->id]);

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->post(route('workspace.notifications.send'), [
                'title' => 'Later Title',
                'body' => 'Later Body',
                'target_type' => 'user',
                'user_ids' => [$user->id],
                'scheduled_at' => '2026-06-26T10:00',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $campaign = NotificationCampaign::where('title', 'Later Title')->firstOrFail();
        $this->assertSame(NotificationCampaign::STATUS_SCHEDULED, $campaign->status);
        $this->assertSame(0, $campaign->targeted_count);

        Carbon::setTestNow(Carbon::parse('2026-06-26 10:01:00'));
        $this->artisan('notifications:dispatch-due-campaigns')->assertSuccessful();

        $campaign->refresh();
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
        $this->assertSame(1, $campaign->sent_count);

        Carbon::setTestNow();
    }

    public function test_skips_user_after_workspace_owner_daily_recipient_limit(): void
    {
        Notification::fake();
        config(['notification_campaigns.per_user_daily_limit' => 5]);

        $user = User::factory()->create();
        $device = $user->devices()->create(['token' => 'limited-token', 'platform' => 'android']);
        WorkspaceVisit::factory()->create(['workspace_id' => $this->workspace->id, 'user_id' => $user->id]);

        for ($i = 0; $i < 5; $i++) {
            $campaign = NotificationCampaign::create([
                'workspace_id' => $this->workspace->id,
                'sender_type' => 'workspace_owner',
                'sender_id' => $this->owner->id,
                'target_type' => 'user',
                'notification_category' => 'workspace_updates',
                'target_payload' => ['user_ids' => [$user->id]],
                'locale' => 'ar',
                'title' => "Existing {$i}",
                'body' => 'Existing',
                'status' => NotificationCampaign::STATUS_SENT,
                'targeted_count' => 1,
                'sent_count' => 1,
            ]);

            $campaign->recipients()->create([
                'user_id' => $user->id,
                'device_token_id' => $device->id,
                'locale' => 'ar',
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->post(route('workspace.notifications.send'), [
                'title' => 'Limited Today',
                'body' => 'Limited Body',
                'target_type' => 'user',
                'user_ids' => [$user->id],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $campaign = NotificationCampaign::where('title', 'Limited Today')->firstOrFail();
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
        $this->assertSame(1, $campaign->targeted_count);
        $this->assertSame(0, $campaign->sent_count);
        $this->assertSame(1, $campaign->skipped_count);

        $this->assertDatabaseHas('notification_recipients', [
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'status' => 'skipped',
            'error_code' => 'daily_recipient_limit',
        ]);
    }

    public function test_can_send_notification_with_image(): void
    {
        Notification::fake();
        Storage::fake('public');

        $user = User::factory()->create();
        $user->devices()->create(['token' => 'test-token-image', 'platform' => 'android']);
        WorkspaceVisit::factory()->create(['workspace_id' => $this->workspace->id, 'user_id' => $user->id]);

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->post(route('workspace.notifications.send'), [
                'title' => 'Promo',
                'body' => 'Promo Body',
                'target_type' => 'all_visitors',
                'image' => $file,
            ]);

        if ($response->isRedirect() && session()->has('errors')) {
            dd(session('errors'));
        }

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $campaign = NotificationCampaign::where('title', 'Promo')->firstOrFail();

        $this->assertNotNull($campaign->image_url);
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
    }

    public function test_validates_request_data(): void
    {
        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->post(route('workspace.notifications.send'), [
                'title' => '',
                'body' => '',
                'target_type' => 'invalid_type',
            ]);

        $response->assertSessionHasErrors(['title', 'body', 'target_type']);
    }

    public function test_fails_if_specific_users_missing_user_ids(): void
    {
        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->post(route('workspace.notifications.send'), [
                'title' => 'T',
                'body' => 'B',
                'target_type' => 'user',
            ]);

        $response->assertSessionHasErrors(['user_ids']);
    }

    public function test_fails_if_session_target_missing_session_id(): void
    {
        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->post(route('workspace.notifications.send'), [
                'title' => 'T',
                'body' => 'B',
                'target_type' => 'public_session',
            ]);

        $response->assertSessionHasErrors(['session_id']);
    }
}
