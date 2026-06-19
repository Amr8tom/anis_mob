<?php

declare(strict_types=1);

namespace App\Domain\Room\Actions;

use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Models\WorkspaceRoom;

final readonly class UpdateRoomAction
{
    public function __construct(private RoomRepositoryInterface $rooms) {}

    public function handle(WorkspaceRoom $room, string $name, int $hourlyPriceCents, bool $isActive): WorkspaceRoom
    {
        // Editing the room price never rewrites existing reservations — they keep
        // their own price snapshot.
        return $this->rooms->update($room, [
            'name' => $name,
            'hourly_price_cents' => $hourlyPriceCents,
            'is_active' => $isActive,
        ]);
    }
}
