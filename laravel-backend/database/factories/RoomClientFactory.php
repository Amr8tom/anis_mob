<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RoomClient;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomClient>
 */
class RoomClientFactory extends Factory
{
    protected $model = RoomClient::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'name' => fake()->name(),
            'phone' => '010'.fake()->unique()->numerify('########'),
            'note' => null,
        ];
    }
}
