<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\Badge;
use App\Models\User;
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
            ->assertJsonStructure(['data' => ['id', 'subscriptionType', 'streakDays', 'totalSessions', 'avatarPath']]);
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
}
