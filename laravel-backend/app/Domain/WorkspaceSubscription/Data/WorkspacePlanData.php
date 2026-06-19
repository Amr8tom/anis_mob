<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Data;

final readonly class WorkspacePlanData
{
    public function __construct(
        public string $name,
        public int $includedMinutes,
        public int $durationDays,
        public ?int $priceCents,
        public string $currency = 'EGP',
        public bool $isActive = true,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toAttributes(string $workspaceId): array
    {
        return [
            'workspace_id' => $workspaceId,
            'name' => $this->name,
            'included_minutes' => $this->includedMinutes,
            'duration_days' => $this->durationDays,
            'price_cents' => $this->priceCents,
            'currency' => $this->currency,
            'is_active' => $this->isActive,
        ];
    }
}
