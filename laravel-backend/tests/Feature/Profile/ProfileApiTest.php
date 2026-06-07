<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\Badge;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_requires_authentication(): void
    {
        $this->getJson('/api/v1/profile')->assertStatus(401);
    }

    public function test_profile_returns_expected_shape_with_badges(): void
    {
        $user = User::factory()->create(['university' => 'Cairo University', 'total_sessions' => 37]);
        $badge = Badge::factory()->create(['label' => 'Streak Master', 'icon_key' => 'streak']);
        $user->badges()->attach($badge->id, ['earned_at' => now()]);

        $this->actingAs($user)
            ->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.name', $user->full_name)
            ->assertJsonPath('data.university', 'Cairo University')
            ->assertJsonPath('data.badges.0.iconKey', 'streak')
            ->assertJsonStructure(['data' => [
                'id', 'subscriptionType', 'streakDays', 'totalSessions', 'avatarPath',
                'profileCompleted', 'profileCompletionPercentage', 'missingProfileFields',
            ]]);
    }

    public function test_profile_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patchJson('/api/v1/profile', ['full_name' => 'New Name', 'university' => 'Ain Shams'])
            ->assertOk()
            ->assertJsonPath('data.name', 'New Name')
            ->assertJsonPath('data.university', 'Ain Shams');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'full_name' => 'New Name']);
    }

    public function test_user_profile_policy_denies_other_users(): void
    {
        $actor = User::factory()->create();
        $other = User::factory()->create();
        $policy = new UserPolicy;

        $this->assertFalse($policy->viewProfile($actor, $other));
        $this->assertFalse($policy->updateProfile($actor, $other));
        $this->assertTrue($policy->viewProfile($actor, $actor));
        $this->assertTrue($policy->updateProfile($actor, $actor));
    }

    public function test_profile_completion_reports_missing_fields(): void
    {
        $user = User::factory()->create([
            'email' => null,
            'university' => null,
            'study_field' => null,
            'gender' => null,
            'interests' => null,
        ]);

        $this->actingAs($user)
            ->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.profileCompleted', false)
            ->assertJsonPath('data.profileCompletionPercentage', 0)
            ->assertJsonPath('data.missingProfileFields', [
                'email', 'university', 'studyField', 'gender', 'interests',
            ]);
    }

    public function test_profile_completion_fields_can_be_saved_after_signup(): void
    {
        $user = User::factory()->create([
            'email' => null,
            'university' => null,
            'study_field' => null,
            'gender' => null,
            'interests' => null,
            'profile_completed_at' => null,
        ]);

        $this->actingAs($user)
            ->patchJson('/api/v1/profile', [
                'email' => 'STUDENT@ANIS.TEST',
                'university' => 'Cairo University',
                'study_field' => 'Computer Engineering',
                'gender' => 'male',
                'interests' => ['Programming', 'Mathematics'],
            ])
            ->assertOk()
            ->assertJsonPath('data.email', 'student@anis.test')
            ->assertJsonPath('data.gender', 'male')
            ->assertJsonPath('data.profileCompleted', true)
            ->assertJsonPath('data.profileCompletionPercentage', 100)
            ->assertJsonPath('data.missingProfileFields', []);

        $this->assertNotNull($user->refresh()->profile_completed_at);
    }

    public function test_clearing_a_required_profile_field_marks_profile_incomplete(): void
    {
        $user = User::factory()->create([
            'email' => 'student@anis.test',
            'university' => 'Cairo University',
            'study_field' => 'Engineering',
            'gender' => 'MALE',
            'interests' => ['Programming'],
            'profile_completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->patchJson('/api/v1/profile', ['university' => null])
            ->assertOk()
            ->assertJsonPath('data.profileCompleted', false)
            ->assertJsonPath('data.missingProfileFields', ['university']);

        $this->assertNull($user->refresh()->profile_completed_at);
    }
}
