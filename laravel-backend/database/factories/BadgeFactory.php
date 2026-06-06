<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Badge>
 */
class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $key = fake()->unique()->randomElement(['streak', 'hours', 'sessions', 'top']);

        return [
            'key' => $key,
            'label' => ucfirst($key),
            'icon_key' => $key,
        ];
    }
}
