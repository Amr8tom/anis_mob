<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Models\AdminAuditLog;

final readonly class LogAdminAction
{
    public function handle(string $adminId, string $action, ?string $entityType = null, ?string $entityId = null, ?array $details = null, ?string $ipAddress = null): AdminAuditLog
    {
        return AdminAuditLog::create([
            'admin_id' => $adminId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details,
            'ip_address' => $ipAddress,
        ]);
    }
}
