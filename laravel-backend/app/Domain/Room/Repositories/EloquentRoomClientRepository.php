<?php

declare(strict_types=1);

namespace App\Domain\Room\Repositories;

use App\Domain\Room\Contracts\RoomClientRepositoryInterface;
use App\Models\RoomClient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentRoomClientRepository implements RoomClientRepositoryInterface
{
    public function findOrCreate(string $workspaceId, string $phone, string $name, ?string $note = null): RoomClient
    {
        $phone = trim($phone);

        $client = RoomClient::query()
            ->where('workspace_id', $workspaceId)
            ->where('phone', $phone)
            ->first();

        if ($client !== null) {
            $updates = [];

            // Keep the latest name and note the owner typed.
            if ($name !== '' && $name !== $client->name) {
                $updates['name'] = $name;
            }

            if (filled($note) && $note !== $client->note) {
                $updates['note'] = $note;
            }

            if ($updates !== []) {
                $client->update($updates);
            }

            return $client;
        }

        return RoomClient::create([
            'workspace_id' => $workspaceId,
            'phone' => $phone,
            'name' => $name,
            'note' => $note,
        ]);
    }

    public function findForWorkspace(string $clientId, string $workspaceId): ?RoomClient
    {
        return RoomClient::query()
            ->where('id', $clientId)
            ->where('workspace_id', $workspaceId)
            ->first();
    }

    public function paginateForWorkspace(string $workspaceId, int $perPage): LengthAwarePaginator
    {
        return RoomClient::query()
            ->withCount('reservations')
            ->where('workspace_id', $workspaceId)
            ->latest('created_at')
            ->paginate($perPage);
    }
}
