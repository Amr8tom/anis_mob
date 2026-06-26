<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\NotificationCampaign;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['notification_campaigns.quiet_hours.enabled' => false]);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => UserRole::ADMIN]);
    }

    public function test_admin_can_preview_workspace_visitor_notification_audience(): void
    {
        $workspace = Workspace::factory()->create();

        $reachableUser = User::factory()->create();
        $reachableUser->devices()->create(['token' => 'admin-preview-token-1', 'platform' => 'android']);
        $reachableUser->devices()->create(['token' => 'admin-preview-token-2', 'platform' => 'ios']);
        WorkspaceVisit::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $reachableUser->id,
        ]);

        $registeredWithoutDevice = User::factory()->create();
        WorkspaceVisit::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => $registeredWithoutDevice->id,
        ]);

        $walkIn = WorkspaceWalkIn::create([
            'workspace_id' => $workspace->id,
            'full_name' => 'Manual Visitor',
            'phone_number' => '01055551111',
        ]);
        WorkspaceVisit::factory()->create([
            'workspace_id' => $workspace->id,
            'user_id' => null,
            'walk_in_id' => $walkIn->id,
        ]);

        $outsideUser = User::factory()->create();
        $outsideUser->devices()->create(['token' => 'admin-outside-token', 'platform' => 'android']);

        $response = $this->actingAs($this->admin(), 'admin')
            ->postJson(route('admin.notifications.preview'), [
                'target_type' => 'workspace_visitors',
                'workspace_id' => $workspace->id,
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

    public function test_admin_notification_page_contains_templates(): void
    {
        $response = $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.notifications.index'));

        $response->assertOk();
        $response->assertSee('adminTemplateSelect');
        $response->assertSee('special_offer');
        $response->assertSee('room_confirmed');
    }

    public function test_admin_can_view_notification_campaign_details(): void
    {
        $campaign = NotificationCampaign::create([
            'sender_type' => 'admin',
            'sender_id' => $this->admin()->id,
            'target_type' => 'all_users',
            'notification_category' => 'workspace_updates',
            'target_payload' => [],
            'locale' => 'ar',
            'title' => 'Admin Details',
            'body' => 'Details Body',
            'status' => NotificationCampaign::STATUS_FAILED,
            'targeted_count' => 2,
            'sent_count' => 1,
            'failed_count' => 1,
        ]);

        $user = User::factory()->create(['full_name' => 'Details User']);
        $device = $user->devices()->create(['token' => 'details-token', 'platform' => 'android']);
        $campaign->recipients()->create([
            'user_id' => $user->id,
            'device_token_id' => $device->id,
            'locale' => 'ar',
            'status' => 'failed',
            'error_code' => 'send_failed',
            'error_message' => 'Temporary provider error',
        ]);

        $response = $this->actingAs($this->admin(), 'admin')
            ->get(route('admin.notifications.show', $campaign));

        $response->assertOk();
        $response->assertSee('Admin Details');
        $response->assertSee('Details User');
        $response->assertSee('Temporary provider error');
        $response->assertSee('إعادة إرسال الفاشل');
    }

    public function test_admin_can_retry_failed_notification_recipients(): void
    {
        Notification::fake();

        $campaign = NotificationCampaign::create([
            'sender_type' => 'admin',
            'sender_id' => $this->admin()->id,
            'target_type' => 'all_users',
            'notification_category' => 'workspace_updates',
            'target_payload' => [],
            'locale' => 'ar',
            'title' => 'Retry Campaign',
            'body' => 'Retry Body',
            'status' => NotificationCampaign::STATUS_FAILED,
            'targeted_count' => 1,
            'failed_count' => 1,
        ]);

        $user = User::factory()->create();
        $device = $user->devices()->create(['token' => 'retry-token', 'platform' => 'ios']);
        $campaign->recipients()->create([
            'user_id' => $user->id,
            'device_token_id' => $device->id,
            'locale' => 'ar',
            'status' => 'failed',
            'error_code' => 'send_failed',
            'error_message' => 'Retry me',
        ]);

        $response = $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.notifications.retry-failed', $campaign));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $campaign->refresh();
        $this->assertSame(NotificationCampaign::STATUS_SENT, $campaign->status);
        $this->assertSame(1, $campaign->sent_count);
        $this->assertSame(0, $campaign->failed_count);

        $this->assertDatabaseHas('notification_recipients', [
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'status' => 'sent',
        ]);
    }
}
