<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

final class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $payload = [
            'full_name' => 'أنس محمد',
            'phone_number' => '01000000001',
            'whatsapp_number' => '01000000001',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ];

        $this->postJson('/api/v1/auth/register', $payload)
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.phone_number', '01000000001')
            ->assertJsonPath('data.user.profile_completed', false)
            ->assertJsonStructure(['success', 'message', 'data' => ['token', 'user' => ['id', 'full_name', 'role']]]);

        $this->assertDatabaseHas('users', ['phone_number' => '01000000001', 'is_guest' => false]);
    }

    public function test_register_rejects_duplicate_phone(): void
    {
        User::factory()->create(['phone_number' => '01000000002']);

        $this->postJson('/api/v1/auth/register', [
            'full_name' => 'Test',
            'phone_number' => '01000000002',
            'whatsapp_number' => '01000000002',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
        ])->assertStatus(422)->assertJsonPath('success', false);
    }

    public function test_register_validates_required_fields(): void
    {
        $this->postJson('/api/v1/auth/register', [])
            ->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_register_requires_a_strong_confirmed_password(): void
    {
        $this->postJson('/api/v1/auth/register', [
            'full_name' => 'Test User',
            'phone_number' => '01000000020',
            'whatsapp_number' => '01000000020',
            'password' => 'weakpass',
            'password_confirmation' => 'different',
        ])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['errors' => ['password']]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        User::factory()->create([
            'phone_number' => '01000000003',
            'password' => Hash::make('secret123'),
        ]);

        $this->postJson('/api/v1/auth/login', [
            'phone_number' => '01000000003',
            'password' => 'secret123',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token', 'user' => ['id', 'full_name', 'role']]]);
    }

    public function test_user_can_login_with_email(): void
    {
        User::factory()->create([
            'email' => 'jane@anis.test',
            'password' => Hash::make('secret123'),
        ]);

        $this->postJson('/api/v1/auth/login', [
            'login' => 'jane@anis.test',
            'password' => 'secret123',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'jane@anis.test');
    }

    public function test_login_requires_an_identifier(): void
    {
        $this->postJson('/api/v1/auth/login', ['password' => 'secret123'])
            ->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'phone_number' => '01000000004',
            'password' => Hash::make('secret123'),
        ]);

        $this->postJson('/api/v1/auth/login', [
            'phone_number' => '01000000004',
            'password' => 'wrong-password',
        ])->assertStatus(401)->assertJsonPath('success', false);
    }

    public function test_register_accepts_gender_and_study_field(): void
    {
        $this->postJson('/api/v1/auth/register', [
            'full_name' => 'عمر علي',
            'phone_number' => '01000000010',
            'whatsapp_number' => '01000000010',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
            'gender' => 'male',
            'study_field' => 'Computer Engineering',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.gender', 'MALE');

        $this->assertDatabaseHas('users', [
            'phone_number' => '01000000010',
            'gender' => 'MALE',
            'study_field' => 'Computer Engineering',
        ]);
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/v1/auth/me')
            ->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    public function test_authenticated_user_can_fetch_profile_and_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
