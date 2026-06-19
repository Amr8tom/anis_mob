<?php

declare(strict_types=1);

namespace App\Domain\Room\Actions;

use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Models\WorkspaceRoom;

final readonly class DeactivateRoomAction
{
    public function __construct(private RoomRepositoryInterface $rooms) {}

    public function handle(WorkspaceRoom $room): WorkspaceRoom
    {
        // Never hard-delete: reservations reference the room. Deactivate only.
        return $this->rooms->deactivate($room);
    }
}
