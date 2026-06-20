<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspacePrivateSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WorkspacePrivateSession>
 */
final class WorkspacePrivateSessionFactory extends Factory
{
    protected $model = WorkspacePrivateSession::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'created_by_owner_id' => User::factory(),
            'title' => 'جلسة خاصة',
            'description' => 'جلسة خاصة بمساحة العمل',
            'host_name' => 'المحاضر',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
            'capacity' => 30,
            'price_cents' => 10000,
            'status' => 'active',
            'qr_token' => Str::random(48),
            'notes' => null,
        ];
    }
}
