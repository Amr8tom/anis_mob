<?php

declare(strict_types=1);

namespace Tests\Feature\Home;

use App\Enums\PlanTier;
use App\Models\Plan;
use App\Models\StudySession;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_profile_requires_authentication(): void
    {
        $this->getJson('/api/v1/home/profile')->assertStatus(401);
    }

    public function test_home_profile_returns_expected_shape(): void
    {
        $user = User::factory()->create(['total_study_hours' => 124, 'streak_days' => 7]);
        $plan = Plan::factory()->create(['tier' => PlanTier::SILVER, 'duration_days' => 30]);
        Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'expires_at' => now()->addDays(8),
        ]);

        $this->actingAs($user)
            ->getJson('/api/v1/home/profile')
            ->assertOk()
            ->assertJsonPath('data.subscriptionType', 'silver')
            ->assertJsonPath('data.subscriptionTotalDays', 30)
            ->assertJsonPath('data.subscriptionDaysRemaining', 8)
            ->assertJsonStructure(['data' => ['id', 'name', 'initials', 'totalStudyHours', 'streakDays']])
            ->assertJsonMissingPath('data.walletBalance');
    }

    public function test_today_sessions_returns_only_todays(): void
    {
        $user = User::factory()->create();
        StudySession::factory()->create(['start_time' => now()->addHour()]);
        StudySession::factory()->create(['start_time' => now()->addDays(3)]);

        $this->actingAs($user)
            ->getJson('/api/v1/home/today-sessions')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonStructure([
                'data' => [['id', 'title', 'university', 'timeLabel', 'tagLabel', 'tagColorKey', 'status', 'participantCount', 'maxParticipants']],
            ]);
    }
}
