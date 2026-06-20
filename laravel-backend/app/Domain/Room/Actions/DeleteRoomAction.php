<?php

declare(strict_types=1);

namespace App\Domain\Room\Actions;

use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Models\WorkspaceRoom;

/**
 * Hard-delete a room and ALL of its reservations. Irreversible — used when the
 * owner wants the room and its history gone entirely (not just deactivated).
 */
final readonly class DeleteRoomAction
{
    public function __construct(private RoomRepositoryInterface $rooms) {}

    public function handle(WorkspaceRoom $room): void
    {
        $this->rooms->deleteWithReservations($room);
    }
}
