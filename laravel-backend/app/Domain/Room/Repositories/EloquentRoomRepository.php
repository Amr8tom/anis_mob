<?php

declare(strict_types=1);

namespace App\Domain\Room\Repositories;

use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Models\WorkspaceRoom;
use Illuminate\Support\Collection;

final class EloquentRoomRepository implements RoomRepositoryInterface
{
    public function create(array $attributes): WorkspaceRoom
    {
        return WorkspaceRoom::create($attributes);
    }

    public function update(WorkspaceRoom $room, array $attributes): WorkspaceRoom
    {
        $room->update($attributes);

        return $room->refresh();
    }

    public function deactivate(WorkspaceRoom $room): WorkspaceRoom
    {
        $room->update(['is_active' => false]);

        return $room->refresh();
    }

    public function findForWorkspace(string $roomId, string $workspaceId): ?WorkspaceRoom
    {
        return WorkspaceRoom::query()
            ->where('id', $roomId)
            ->where('workspace_id', $workspaceId)
            ->first();
    }

    public function lockActive(string $roomId, string $workspaceId): ?WorkspaceRoom
    {
        return WorkspaceRoom::query()
            ->where('id', $roomId)
            ->where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->lockForUpdate()
            ->first();
    }

    public function activeForWorkspace(string $workspaceId): Collection
    {
        return WorkspaceRoom::query()
            ->where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->orderBy('position')
            ->orderBy('name')
            ->get();
    }

    public function allForWorkspace(string $workspaceId): Collection
    {
        return WorkspaceRoom::query()
            ->where('workspace_id', $workspaceId)
            ->orderBy('position')
            ->orderBy('name')
            ->get();
    }
}
