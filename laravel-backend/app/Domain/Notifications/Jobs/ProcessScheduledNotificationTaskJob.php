<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Jobs;

use App\Domain\Notifications\Actions\CreateNotificationCampaignAction;
use App\Domain\Notifications\Services\AutomaticNotificationTemplateRenderer;
use App\Enums\SessionStatus;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\ScheduledNotificationTask;
use App\Models\StudySession;
use App\Models\UserNotificationPreference;
use App\Models\Workspace;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspaceSubscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ProcessScheduledNotificationTaskJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly string $taskId)
    {
        $this->onQueue((string) config('notification_campaigns.queue', 'notifications'));
    }

    public function handle(
        CreateNotificationCampaignAction $createCampaign,
        AutomaticNotificationTemplateRenderer $renderer,
    ): void {
        $task = $this->claimTask();

        if (! $task instanceof ScheduledNotificationTask) {
            return;
        }

        try {
            $resolved = $this->resolveTask($task);

            if ($resolved === null) {
                $this->cancelTask($task, 'The notification target is no longer valid.');

                return;
            }

            [$workspace, $entity, $targetType, $targetPayload, $notificationData] = $resolved;
            $message = $renderer->render($task->type, $workspace, $entity);

            $campaign = $createCampaign->execute([
                'workspace_id' => $workspace->id,
                'sender_type' => 'system',
                'sender_id' => null,
                'target_type' => $targetType,
                'notification_category' => $this->categoryForTask($task->type),
                'target_payload' => array_merge($targetPayload, [
                    'automatic_task_id' => $task->id,
                    'automatic_type' => $task->type,
                    'notification_data' => $notificationData,
                ]),
                'locale' => $message['locale'],
                'title' => $message['title'],
                'body' => $message['body'],
                'image_url' => null,
            ]);

            $task->forceFill([
                'status' => ScheduledNotificationTask::STATUS_PROCESSED,
                'campaign_id' => $campaign->id,
                'processed_at' => now(),
                'error_message' => null,
            ])->save();
        } catch (Throwable $exception) {
            report($exception);

            $task->forceFill([
                'status' => ScheduledNotificationTask::STATUS_FAILED,
                'error_message' => mb_substr($exception->getMessage(), 0, 1000),
            ])->save();
        }
    }

    private function claimTask(): ?ScheduledNotificationTask
    {
        return DB::transaction(function (): ?ScheduledNotificationTask {
            $task = ScheduledNotificationTask::query()
                ->whereKey($this->taskId)
                ->lockForUpdate()
                ->first();

            if (
                $task === null
                || ! in_array($task->status, [
                    ScheduledNotificationTask::STATUS_PENDING,
                    ScheduledNotificationTask::STATUS_QUEUED,
                ], true)
                || $task->due_at->isFuture()
            ) {
                return null;
            }

            $task->forceFill([
                'status' => ScheduledNotificationTask::STATUS_PROCESSING,
            ])->save();

            return $task;
        });
    }

    /**
     * @return array{0:Workspace,1:StudySession|WorkspacePrivateSession|WorkspaceSubscription,2:string,3:array<string,mixed>,4:array<string,mixed>}|null
     */
    private function resolveTask(ScheduledNotificationTask $task): ?array
    {
        return match ($task->type) {
            ScheduledNotificationTask::TYPE_PUBLIC_SESSION_18H => $this->resolvePublicSessionReminder($task),
            ScheduledNotificationTask::TYPE_PRIVATE_SESSION_18H => $this->resolvePrivateSessionReminder($task),
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D => $this->resolveSubscriptionExpiryReminder($task),
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H => $this->resolveSubscriptionLowHoursReminder($task),
            default => null,
        };
    }

    private function resolvePublicSessionReminder(ScheduledNotificationTask $task): ?array
    {
        $session = StudySession::with('workspace')->find($task->entity_id);

        if (
            ! $session instanceof StudySession
            || ! $session->workspace instanceof Workspace
            || $session->status !== SessionStatus::UPCOMING
            || $session->start_time === null
            || $session->start_time->isPast()
        ) {
            return null;
        }

        return [
            $session->workspace,
            $session,
            'public_session',
            [
                'workspace_id' => $session->workspace_id,
                'session_id' => $session->id,
            ],
            [
                'type' => 'session_reminder',
                'reminder_type' => '18h',
                'session_type' => 'public',
                'deep_link_type' => 'public_session',
                'workspace_id' => $session->workspace_id,
                'session_id' => $session->id,
            ],
        ];
    }

    private function resolvePrivateSessionReminder(ScheduledNotificationTask $task): ?array
    {
        $session = WorkspacePrivateSession::with('workspace')->find($task->entity_id);

        if (
            ! $session instanceof WorkspacePrivateSession
            || ! $session->workspace instanceof Workspace
            || $session->status !== 'active'
            || $session->starts_at === null
            || $session->starts_at->isPast()
        ) {
            return null;
        }

        return [
            $session->workspace,
            $session,
            'private_session',
            [
                'workspace_id' => $session->workspace_id,
                'session_id' => $session->id,
            ],
            [
                'type' => 'session_reminder',
                'reminder_type' => '18h',
                'session_type' => 'private',
                'deep_link_type' => 'private_session',
                'workspace_id' => $session->workspace_id,
                'session_id' => $session->id,
            ],
        ];
    }

    private function resolveSubscriptionExpiryReminder(ScheduledNotificationTask $task): ?array
    {
        $subscription = WorkspaceSubscription::with('workspace')->find($task->entity_id);

        if (
            ! $subscription instanceof WorkspaceSubscription
            || ! $subscription->workspace instanceof Workspace
            || $subscription->status !== WorkspaceSubscriptionStatus::ACTIVE
            || $subscription->user_id === null
            || $subscription->expires_at === null
            || $subscription->expires_at->isPast()
            || $subscription->remaining_minutes <= 0
        ) {
            return null;
        }

        return $this->subscriptionResult($subscription, 'workspace_subscription_expiry', '2d');
    }

    private function resolveSubscriptionLowHoursReminder(ScheduledNotificationTask $task): ?array
    {
        $subscription = WorkspaceSubscription::with('workspace')->find($task->entity_id);

        if (
            ! $subscription instanceof WorkspaceSubscription
            || ! $subscription->workspace instanceof Workspace
            || $subscription->status !== WorkspaceSubscriptionStatus::ACTIVE
            || $subscription->user_id === null
            || $subscription->remaining_minutes <= 0
            || $subscription->remaining_minutes >= 16 * 60
        ) {
            return null;
        }

        return $this->subscriptionResult($subscription, 'workspace_subscription_low_hours', '16h');
    }

    private function subscriptionResult(WorkspaceSubscription $subscription, string $notificationType, string $reminderType): array
    {
        return [
            $subscription->workspace,
            $subscription,
            'workspace_subscription',
            [
                'workspace_id' => $subscription->workspace_id,
                'subscription_id' => $subscription->id,
            ],
            [
                'type' => $notificationType,
                'reminder_type' => $reminderType,
                'deep_link_type' => 'workspace_subscription',
                'workspace_id' => $subscription->workspace_id,
                'subscription_id' => $subscription->id,
            ],
        ];
    }

    private function categoryForTask(string $type): string
    {
        return match ($type) {
            ScheduledNotificationTask::TYPE_PUBLIC_SESSION_18H,
            ScheduledNotificationTask::TYPE_PRIVATE_SESSION_18H => UserNotificationPreference::CATEGORY_SESSION_REMINDERS,
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D,
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H => UserNotificationPreference::CATEGORY_SUBSCRIPTION_ALERTS,
            default => UserNotificationPreference::CATEGORY_WORKSPACE_UPDATES,
        };
    }

    private function cancelTask(ScheduledNotificationTask $task, string $reason): void
    {
        $task->forceFill([
            'status' => ScheduledNotificationTask::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'error_message' => $reason,
        ])->save();
    }
}
