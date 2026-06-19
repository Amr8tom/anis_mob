<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_saves_submitted_password_and_allows_login(): void
    {
        $payload = [
            'full_name' => 'Test User',
            'phone_number' => '01012345678',
            'whatsapp_number' => '01012345678',
            'password' => 'secretPassword123',
            'password_confirmation' => 'secretPassword123',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'phone_number' => '01012345678',
        ]);

        $user = User::where('phone_number', '01012345678')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('secretPassword123', $user->password));

        // Attempt login
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'phone_number' => '01012345678',
            'password' => 'secretPassword123',
        ]);

        $loginResponse->assertStatus(200);
        $loginResponse->assertJsonStructure(['data' => ['token']]);
    }

    public function test_empty_password_cannot_be_stored(): void
    {
        $payload = [
            'full_name' => 'Test User',
            'phone_number' => '01012345678',
            'whatsapp_number' => '01012345678',
            'password' => '',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }
}
