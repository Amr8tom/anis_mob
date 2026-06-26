<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Actions;

use App\Models\ScheduledNotificationTask;
use Illuminate\Support\Carbon;

final class UpsertScheduledNotificationTaskAction
{
    public function execute(
        string $type,
        string $entityType,
        ?string $entityId,
        Carbon $dueAt,
        string $idempotencyKey,
        ?string $workspaceId = null,
        array $payload = [],
    ): ScheduledNotificationTask {
        $task = ScheduledNotificationTask::firstOrNew(['idempotency_key' => $idempotencyKey]);

        if ($task->exists && in_array($task->status, [
            ScheduledNotificationTask::STATUS_QUEUED,
            ScheduledNotificationTask::STATUS_PROCESSING,
            ScheduledNotificationTask::STATUS_PROCESSED,
        ], true)) {
            return $task;
        }

        $task->forceFill([
            'workspace_id' => $workspaceId,
            'type' => $type,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'due_at' => $dueAt,
            'status' => ScheduledNotificationTask::STATUS_PENDING,
            'payload' => $payload,
            'cancelled_at' => null,
            'error_message' => null,
        ])->save();

        return $task;
    }
}
