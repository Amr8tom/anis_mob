<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\StudySession;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudySession>
 */
class StudySessionFactory extends Factory
{
    protected $model = StudySession::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now()->addHours(fake()->numberBetween(1, 48));

        return [
            'workspace_id' => Workspace::factory(),
            'host_id' => User::factory(),
            'title' => fake()->randomElement(['الفيزياء — الفصل 4', 'مراجعة رياضيات', 'English Speaking']),
            'subject' => fake()->randomElement(['فيزياء', 'رياضيات', 'لغة إنجليزية']),
            'description' => fake()->sentence(),
            'rules' => ['الالتزام بالهدوء', 'الحضور في الميعاد'],
            'gift' => fake()->randomElement([null, 'مشروب مجاني']),
            'type' => SessionType::STUDY_GROUP,
            'status' => SessionStatus::UPCOMING,
            'start_time' => $start,
            'end_time' => (clone $start)->addHours(2),
            'max_seats' => fake()->numberBetween(4, 10),
            'time_label' => $start->format('g:i A'),
            'tag_label' => fake()->randomElement(['فيز', 'ريض', 'إنج']),
            'tag_color_key' => fake()->randomElement(['blue', 'yellow', 'pink', 'green']),
        ];
    }
}
