<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Admin-control lifecycle axis for a workspace. Orthogonal to the operational
 * `status` (OPEN/BUSY/FULL/CLOSED) and to `is_active` (owner open/closed toggle).
 *
 * State machine:
 *   PENDING   --approve-->  APPROVED
 *   PENDING   --reject-->   (hard-deleted)
 *   APPROVED  <-> SUSPENDED (freeze / unfreeze)
 *   APPROVED | SUSPENDED --delete--> (soft-deleted)
 */
enum WorkspaceLifecycleStatus: string
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case SUSPENDED = 'SUSPENDED';
    case REJECTED = 'REJECTED';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'بانتظار المراجعة',
            self::APPROVED => 'مفعّلة',
            self::SUSPENDED => 'موقوفة مؤقتًا',
            self::REJECTED => 'مرفوضة',
        };
    }
}
