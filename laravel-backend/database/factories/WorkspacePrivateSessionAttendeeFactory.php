<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Workspace;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkspacePrivateSessionAttendee>
 */
final class WorkspacePrivateSessionAttendeeFactory extends Factory
{
    protected $model = WorkspacePrivateSessionAttendee::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phone = '010'.fake()->unique()->numerify('########');

        return [
            'workspace_private_session_id' => WorkspacePrivateSession::factory(),
            'workspace_id' => Workspace::factory(),
            'user_id' => null,
            'walk_in_id' => null,
            'name_snapshot' => fake()->name(),
            'phone_snapshot' => $phone,
            'phone_normalized' => preg_replace('/\D+/', '', $phone),
            'source' => 'manual',
            'status' => 'invited',
            'checked_in_at' => null,
            'checked_in_method' => null,
            'checked_in_by_owner_id' => null,
            'amount_cents' => 10000,
            'payment_status' => 'paid',
        ];
    }
}
