<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<WorkspaceClient> */
final class WorkspaceClientFactory extends Factory
{
    protected $model = WorkspaceClient::class;

    public function definition(): array
    {
        $phone = $this->faker->numerify('010########');

        return [
            'workspace_id' => Workspace::factory(),
            'user_id' => User::factory(),
            'walk_in_id' => null,
            'client_type' => 'USER',
            'full_name_snapshot' => $this->faker->name(),
            'phone_number_snapshot' => $phone,
            'phone_number_normalized' => preg_replace('/\D+/', '', $phone),
            'first_visit_at' => now()->subDays(10),
            'last_visit_at' => now(),
            'total_visits' => 1,
            'total_minutes' => 60,
            'free_visits' => 1,
            'global_subscription_visits' => 0,
            'workspace_subscription_visits' => 0,
            'last_billing_source' => 'FREE',
        ];
    }
}
