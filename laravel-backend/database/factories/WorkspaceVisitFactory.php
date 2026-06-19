<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PlanTier;
use App\Enums\VisitStatus;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkspaceVisit>
 */
class WorkspaceVisitFactory extends Factory
{
    protected $model = WorkspaceVisit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'workspace_id' => Workspace::factory(),
            'subscription_id' => null,
            'plan_tier_snapshot' => PlanTier::FREE,
            'status' => VisitStatus::CHECKED_IN,
            'check_in_at' => now(),
            'check_out_at' => null,
            'duration_minutes' => null,
            'checkout_requested_at' => null,
            'active_flag' => 1,   // enforces one active visit per user
        ];
    }

    public function checkedOut(int $minutes = 90): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VisitStatus::CHECKED_OUT,
            'check_out_at' => now(),
            'duration_minutes' => $minutes,
            'billable_minutes' => $minutes,
            'deducted_minutes' => 0,
            'active_flag' => null,
        ]);
    }

    public function checkoutRequested(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VisitStatus::CHECKED_IN,
            'checkout_requested_at' => now(),
        ]);
    }
}
