<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\WorkspaceStatus;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workspace>
 */
class WorkspaceFactory extends Factory
{
    protected $model = Workspace::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'qr_token' => 'ws_'.fake()->unique()->uuid(),
            'name' => fake()->company().' Space',
            'description' => fake()->sentence(),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(29, 31),
            'longitude' => fake()->longitude(30, 32),
            'cover_image_url' => null,
            'gallery_images' => [],
            'amenities' => fake()->randomElements(['wifi', 'ac', 'coffee', 'printing', 'quiet'], 3),
            'status' => WorkspaceStatus::OPEN,
            'capacity' => fake()->numberBetween(20, 120),
            'open_time' => '08:00',
            'close_time' => '23:00',
            'day_calculation_hours' => 8,
            'checkout_mode' => 'DIRECT',
            'payout_rate_cents_per_hour' => 1500,
            'payout_currency' => 'EGP',
            'is_active' => true,
        ];
    }
}
