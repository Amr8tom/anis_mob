<?php

declare(strict_types=1);

namespace App\Domain\Room\Actions;

use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Models\WorkspaceRoom;

final readonly class CreateRoomAction
{
    public function __construct(private RoomRepositoryInterface $rooms) {}

    public function handle(string $workspaceId, string $name, int $hourlyPriceCents, ?string $note = null): WorkspaceRoom
    {
        return $this->rooms->create([
            'workspace_id' => $workspaceId,
            'name' => $name,
            'hourly_price_cents' => $hourlyPriceCents,
            'note' => filled($note) ? trim((string) $note) : null,
            'is_active' => true,
        ]);
    }
}
