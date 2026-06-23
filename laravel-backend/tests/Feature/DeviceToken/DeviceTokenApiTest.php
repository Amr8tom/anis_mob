<?php

declare(strict_types=1);

namespace Tests\Feature\DeviceToken;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class DeviceTokenApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registering_a_token_requires_authentication(): void
    {
        $this->postJson('/api/v1/device-tokens', ['token' => 'abc'])
            ->assertStatus(401);
    }

    public function test_authenticated_user_can_register_a_token(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/device-tokens', [
            'token' => 'fcm-token-123',
            'platform' => 'android',
        ])->assertOk();

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->id,
            'token' => 'fcm-token-123',
            'platform' => 'android',
        ]);
    }

    public function test_registering_is_idempotent_and_rebinds_token_to_current_user(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();

        Sanctum::actingAs($first);
        $this->postJson('/api/v1/device-tokens', ['token' => 'shared-token'])->assertOk();

        // Same physical device now logs in as another user — token must move, not duplicate.
        Sanctum::actingAs($second);
        $this->postJson('/api/v1/device-tokens', ['token' => 'shared-token'])->assertOk();

        $this->assertSame(1, DeviceToken::where('token', 'shared-token')->count());
        $this->assertDatabaseHas('device_tokens', [
            'token' => 'shared-token',
            'user_id' => $second->id,
        ]);
    }

    public function test_invalid_platform_is_rejected(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/device-tokens', [
            'token' => 'tok',
            'platform' => 'windows',
        ])->assertStatus(422);
    }

    public function test_user_can_unregister_their_token(): void
    {
        $user = User::factory()->create();
        DeviceToken::create(['user_id' => $user->id, 'token' => 'to-remove']);

        Sanctum::actingAs($user);
        $this->deleteJson('/api/v1/device-tokens', ['token' => 'to-remove'])->assertOk();

        $this->assertDatabaseMissing('device_tokens', ['token' => 'to-remove']);
    }

    public function test_routing_returns_all_device_tokens_for_fcm(): void
    {
        $user = User::factory()->create();
        DeviceToken::create(['user_id' => $user->id, 'token' => 't1']);
        DeviceToken::create(['user_id' => $user->id, 'token' => 't2']);

        $this->assertEqualsCanonicalizing(['t1', 't2'], $user->routeNotificationForFcm());
    }
}
