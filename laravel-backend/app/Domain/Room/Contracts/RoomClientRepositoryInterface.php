<?php

declare(strict_types=1);

namespace App\Domain\Room\Contracts;

use App\Models\RoomClient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RoomClientRepositoryInterface
{
    /**
     * Find the workspace client by phone, or create it (auto-register on first use).
     */
    public function findOrCreate(string $workspaceId, string $phone, string $name, ?string $note = null): RoomClient;

    public function findForWorkspace(string $clientId, string $workspaceId): ?RoomClient;

    /** @return LengthAwarePaginator<int, RoomClient> */
    public function paginateForWorkspace(string $workspaceId, int $perPage): LengthAwarePaginator;
}
