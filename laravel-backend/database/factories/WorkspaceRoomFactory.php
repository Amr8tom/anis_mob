<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Workspace;
use App\Models\WorkspaceRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkspaceRoom>
 */
class WorkspaceRoomFactory extends Factory
{
    protected $model = WorkspaceRoom::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'name' => 'غرفة اجتماعات',
            'note' => null,
            'hourly_price_cents' => 5000,
            'position' => 0,
            'is_active' => true,
        ];
    }
}
