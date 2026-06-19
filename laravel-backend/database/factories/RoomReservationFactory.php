<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RoomClient;
use App\Models\RoomReservation;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomReservation>
 */
class RoomReservationFactory extends Factory
{
    protected $model = RoomReservation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now()->addDay()->setTime(16, 0);
        $end = (clone $start)->addHours(2);
        $price = 5000;
        $minutes = (int) abs($start->diffInMinutes($end));

        return [
            'workspace_id' => Workspace::factory(),
            'room_id' => WorkspaceRoom::factory(),
            'room_client_id' => RoomClient::factory(),
            'client_name' => 'أحمد محمود',
            'client_phone' => '01000000000',
            'starts_at' => $start,
            'ends_at' => $end,
            'hourly_price_cents' => $price,
            'total_cost_cents' => (int) round($minutes / 60 * $price),
            'status' => 'RESERVED',
            'note' => null,
            'series_id' => null,
            'created_by_owner_id' => User::factory(),
        ];
    }
}
