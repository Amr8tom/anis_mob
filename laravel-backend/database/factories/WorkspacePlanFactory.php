<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Workspace;
use App\Models\WorkspacePlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkspacePlan>
 */
class WorkspacePlanFactory extends Factory
{
    protected $model = WorkspacePlan::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'name' => 'باقة المذاكرة',
            'included_minutes' => 20 * 60,
            'duration_days' => 30,
            'price_cents' => 50000,
            'currency' => 'EGP',
            'is_active' => true,
        ];
    }
}
