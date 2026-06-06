<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Availability;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'full_name' => $name,
            'phone_number' => '01'.fake()->unique()->numerify('#########'),
            'whatsapp_number' => '01'.fake()->numerify('#########'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => UserRole::USER,
            'is_guest' => false,
            'avatar_url' => null,
            'initials' => Str::upper(Str::substr($name, 0, 2)),
            'university' => fake()->randomElement(['جامعة القاهرة', 'جامعة عين شمس', 'جامعة حلوان']),
            'study_field' => fake()->randomElement(['هندسة', 'طب', 'حاسبات', 'تجارة']),
            'interests' => fake()->randomElements(['برمجة', 'رياضيات', 'فيزياء', 'لغات'], 2),
            'avatar_color_key' => fake()->randomElement(['red', 'blue', 'purple', 'green', 'orange']),
            'availability' => Availability::OFFLINE,
            'rating' => fake()->randomFloat(2, 3, 5),
            'wallet_balance' => fake()->randomFloat(2, 0, 200),
            'total_study_hours' => fake()->numberBetween(0, 300),
            'streak_days' => fake()->numberBetween(0, 60),
            'total_sessions' => fake()->numberBetween(0, 40),
            'remember_token' => Str::random(10),
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_guest' => true,
        ]);
    }
}
