<?php

declare(strict_types=1);

namespace Tests\Feature\WorkspacePortal;

use App\Models\StudySession;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceOwner;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspaceVisit;
use App\Notifications\WorkspaceBroadcastNotification;
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
        
        $this->owner = WorkspaceOwner::factory()->create();
        $this->workspace = Workspace::factory()->create(['workspace_owner_id' => $this->owner->id]);
    }

    public function test_can_view_notifications_page(): void
    {
        $response = $this->actingAs($this->owner, 'workspace_owner')
            ->get(route('workspace.notifications.index'));

        $response->assertOk();
        $response->assertSee('target_type');
    }

    public function test_search_users_returns_only_registered_visitors(): void
    {
        // User 1: visited this workspace, registered
        $user1 = User::factory()->create(['full_name' => 'Alice Registered', 'phone_number' => '11111111']);
        $user1->devices()->create(['token' => 'token1', 'device_type' => 'android']);
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

    public function test_can_send_notification_to_specific_users(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->devices()->create(['token' => 'test-token-specific', 'device_type' => 'android']);
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

        Notification::assertSentTo(
            $user,
            WorkspaceBroadcastNotification::class,
            function ($notification) {
                return $notification->title === 'Test Title' && $notification->body === 'Test Body';
            }
        );
    }

    public function test_can_send_notification_with_image(): void
    {
        Notification::fake();
        Storage::fake('public');

        $user = User::factory()->create();
        $user->devices()->create(['token' => 'test-token-image', 'device_type' => 'android']);
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

        Notification::assertSentTo(
            $user,
            WorkspaceBroadcastNotification::class,
            function ($notification) {
                return $notification->imageUrl !== null;
            }
        );
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
