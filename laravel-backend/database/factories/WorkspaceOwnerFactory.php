<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\WorkspaceOwner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<WorkspaceOwner>
 */
class WorkspaceOwnerFactory extends Factory
{
    protected $model = WorkspaceOwner::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'phone_number' => '010'.fake()->unique()->numerify('########'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'status' => 'active',
            'last_login_at' => null,
        ];
    }
}
