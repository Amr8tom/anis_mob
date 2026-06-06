<?php

declare(strict_types=1);

namespace Tests\Feature\Buddy;

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\StudySession;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class BuddySessionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_is_public_and_camelcase(): void
    {
        StudySession::factory()->count(2)->create();

        $this->getJson('/api/v1/buddy-sessions')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [['id', 'buddyName', 'avatarColorKey', 'topic', 'maxCapacity', 'sessionStatus', 'workspace', 'members']],
                'meta' => ['current_page', 'total'],
            ]);
    }

    public function test_detail_is_public(): void
    {
        $session = StudySession::factory()->create();

        $this->getJson("/api/v1/buddy-sessions/{$session->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $session->id)
            ->assertJsonPath('data.sessionStatus', 'open');
    }

    public function test_all_filter_is_accepted_for_older_clients(): void
    {
        StudySession::factory()->create();

        $this->getJson('/api/v1/buddy-sessions?filter=all')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
    }

    public function test_today_and_this_week_filters_match_the_mobile_app(): void
    {
        StudySession::factory()->create(['start_time' => now()->addHour()]);
        StudySession::factory()->create(['start_time' => now()->addWeeks(2)]);

        $this->getJson('/api/v1/buddy-sessions?filter=today')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);

        $this->getJson('/api/v1/buddy-sessions?filter=thisWeek')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
    }

    public function test_list_excludes_non_buddy_and_closed_sessions(): void
    {
        StudySession::factory()->create();
        StudySession::factory()->create(['type' => SessionType::EVENT]);
        StudySession::factory()->create(['status' => SessionStatus::ENDED]);

        $this->getJson('/api/v1/buddy-sessions')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
    }

    public function test_create_requires_authentication(): void
    {
        $workspace = Workspace::factory()->create();

        $this->postJson('/api/v1/buddy-sessions', [
            'topic' => 'Calculus', 'subject' => 'Math', 'workspaceId' => $workspace->id,
            'startTime' => now()->addDay()->toIso8601String(), 'maxCapacity' => 6,
        ])->assertStatus(401);
    }

    public function test_authenticated_user_can_create_session(): void
    {
        $user = User::factory()->create();
        $workspace = Workspace::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/v1/buddy-sessions', [
                'topic' => 'Calculus', 'subject' => 'Math', 'description' => 'Study group',
                'rules' => ['Be on time'], 'workspaceId' => $workspace->id,
                'startTime' => now()->addDay()->toIso8601String(), 'maxCapacity' => 6, 'gift' => null,
            ])
            ->assertCreated()
            ->assertJsonPath('data.topic', 'Calculus')
            ->assertJsonPath('data.buddyName', $user->full_name);

        $this->assertDatabaseHas('study_sessions', ['host_id' => $user->id, 'title' => 'Calculus']);
    }

    public function test_create_rejects_unknown_workspace(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/v1/buddy-sessions', [
                'topic' => 'Calculus', 'subject' => 'Math',
                'workspaceId' => '00000000-0000-0000-0000-000000000000',
                'startTime' => now()->addDay()->toIso8601String(), 'maxCapacity' => 6,
            ])
            ->assertStatus(422);
    }

    public function test_user_can_join_session(): void
    {
        $user = User::factory()->create();
        $session = StudySession::factory()->create(['max_seats' => 5]);

        $this->actingAs($user)
            ->postJson("/api/v1/buddy-sessions/{$session->id}/join")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('session_participants', ['session_id' => $session->id, 'user_id' => $user->id]);
    }

    public function test_duplicate_join_is_rejected(): void
    {
        $user = User::factory()->create();
        $session = StudySession::factory()->create(['max_seats' => 5]);
        $session->participants()->attach($user->id, ['joined_at' => now()]);

        $this->actingAs($user)
            ->postJson("/api/v1/buddy-sessions/{$session->id}/join")
            ->assertStatus(409);
    }

    public function test_full_session_cannot_be_joined(): void
    {
        $existing = User::factory()->create();
        $joiner = User::factory()->create();
        $session = StudySession::factory()->create(['max_seats' => 1]);
        $session->participants()->attach($existing->id, ['joined_at' => now()]);

        $this->actingAs($joiner)
            ->postJson("/api/v1/buddy-sessions/{$session->id}/join")
            ->assertStatus(409);
    }
}
