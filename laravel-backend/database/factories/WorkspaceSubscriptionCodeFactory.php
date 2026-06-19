<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceSubscriptionCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WorkspaceSubscriptionCode>
 */
class WorkspaceSubscriptionCodeFactory extends Factory
{
    protected $model = WorkspaceSubscriptionCode::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'workspace_plan_id' => WorkspacePlan::factory(),
            'code' => 'WSP-'.strtoupper(Str::random(4)).'-'.strtoupper(Str::random(4)),
            'status' => 'UNUSED',
            'created_by_owner_id' => User::factory(),
            'redeemed_by_user_id' => null,
            'workspace_subscription_id' => null,
            'expires_at' => now()->addDays(30),
        ];
    }
}
