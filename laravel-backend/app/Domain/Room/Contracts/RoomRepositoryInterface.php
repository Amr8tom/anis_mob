<?php

declare(strict_types=1);

namespace App\Domain\Room\Contracts;

use App\Models\WorkspaceRoom;
use Illuminate\Support\Collection;

interface RoomRepositoryInterface
{
    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): WorkspaceRoom;

    /** @param array<string, mixed> $attributes */
    public function update(WorkspaceRoom $room, array $attributes): WorkspaceRoom;

    public function deactivate(WorkspaceRoom $room): WorkspaceRoom;

    /** Hard-delete the room together with all its reservations. */
    public function deleteWithReservations(WorkspaceRoom $room): void;

    public function findForWorkspace(string $roomId, string $workspaceId): ?WorkspaceRoom;

    /** Row-locked active room for a safe overlap check inside a transaction. */
    public function lockActive(string $roomId, string $workspaceId): ?WorkspaceRoom;

    /** @return Collection<int, WorkspaceRoom> */
    public function activeForWorkspace(string $workspaceId): Collection;

    /** @return Collection<int, WorkspaceRoom> */
    public function allForWorkspace(string $workspaceId): Collection;
}
