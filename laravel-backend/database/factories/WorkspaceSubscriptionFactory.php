<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkspaceSubscription>
 */
class WorkspaceSubscriptionFactory extends Factory
{
    protected $model = WorkspaceSubscription::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $minutes = 20 * 60;

        return [
            'workspace_id' => Workspace::factory(),
            'workspace_plan_id' => WorkspacePlan::factory(),
            'user_id' => User::factory(),
            'status' => 'ACTIVE',
            'active_flag' => 1,
            'started_at' => now(),
            'expires_at' => now()->addDays(30),
            'remaining_minutes' => $minutes,
            'total_minutes' => $minutes,
            'plan_name_snapshot' => 'باقة المذاكرة',
            'duration_days_snapshot' => 30,
            'price_cents_snapshot' => 50000,
            'currency' => 'EGP',
            'delivery_method' => 'ACTIVATION_CODE',
            'issued_by_owner_id' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'EXPIRED',
            'active_flag' => null,
            'expires_at' => now()->subDay(),
        ]);
    }
}
