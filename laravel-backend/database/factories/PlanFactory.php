<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PlanTier;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Monthly', '20 Hours', 'Weekly']),
            'tier' => PlanTier::SILVER,
            'description' => fake()->sentence(),
            'price_cents' => fake()->randomElement([0, 9900, 19900, 29900]),
            'currency' => 'EGP',
            'included_minutes' => fake()->randomElement([null, 1200, 2400]),
            'duration_days' => fake()->randomElement([null, 7, 30]),
            'is_active' => true,
        ];
    }
}
