<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Workspace;
use App\Models\WorkspaceDrink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkspaceDrink>
 */
class WorkspaceDrinkFactory extends Factory
{
    protected $model = WorkspaceDrink::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'name' => fake()->randomElement(['قهوة', 'شاي', 'عصير', 'مياه']),
            'icon' => fake()->randomElement(['coffee', 'tea', 'juice', 'water']),
            'price_cents' => fake()->randomElement([1500, 2000, 2500, 3000]),
        ];
    }
}
