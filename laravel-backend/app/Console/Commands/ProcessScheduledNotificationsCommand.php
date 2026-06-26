<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Notifications\Actions\ScheduleAutomaticNotificationTaskAction;
use App\Domain\Notifications\Jobs\ProcessScheduledNotificationTaskJob;
use App\Enums\SessionStatus;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\ScheduledNotificationTask;
use App\Models\StudySession;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspaceSubscription;
use Illuminate\Console\Command;

final class ProcessScheduledNotificationsCommand extends Command
{
    protected $signature = 'notifications:process-scheduled
        {--scan-limit=5000 : Maximum source records to scan per type}
        {--dispatch-limit=1000 : Maximum due scheduled tasks to queue per run}';

    protected $description = 'Create and process due automatic notification tasks.';

    public function handle(ScheduleAutomaticNotificationTaskAction $scheduler): int
    {
        $scanLimit = max(1, (int) $this->option('scan-limit'));
        $dispatchLimit = max(1, (int) $this->option('dispatch-limit'));

        $scheduledCount = 0;
        $scheduledCount += $this->schedulePublicSessionReminders($scheduler, $scanLimit);
        $scheduledCount += $this->schedulePrivateSessionReminders($scheduler, $scanLimit);
        $scheduledCount += $this->scheduleSubscriptionExpiryReminders($scheduler, $scanLimit);
        $scheduledCount += $this->scheduleSubscriptionLowHoursReminders($scheduler, $scanLimit);

        $queuedCount = $this->queueDueTasks($dispatchLimit);

        $this->info("Scheduled {$scheduledCount} task(s), queued {$queuedCount} due task(s).");

        return self::SUCCESS;
    }

    private function schedulePublicSessionReminders(ScheduleAutomaticNotificationTaskAction $scheduler, int $limit): int
    {
        $count = 0;
        $latestStart = now()->addHours(18)->addMinutes(5);

        StudySession::query()
            ->where('status', SessionStatus::UPCOMING->value)
            ->where('start_time', '>', now())
            ->where('start_time', '<=', $latestStart)
            ->select(['id', 'workspace_id', 'status', 'start_time', 'title'])
            ->limit($limit)
            ->lazyById(500)
            ->each(function (StudySession $session) use ($scheduler, &$count): void {
                if ($scheduler->publicSessionReminder($session) !== null) {
                    $count++;
                }
            });

        return $count;
    }

    private function schedulePrivateSessionReminders(ScheduleAutomaticNotificationTaskAction $scheduler, int $limit): int
    {
        $count = 0;
        $latestStart = now()->addHours(18)->addMinutes(5);

        WorkspacePrivateSession::query()
            ->where('status', 'active')
            ->where('starts_at', '>', now())
            ->where('starts_at', '<=', $latestStart)
            ->select(['id', 'workspace_id', 'status', 'starts_at', 'title'])
            ->limit($limit)
            ->lazyById(500)
            ->each(function (WorkspacePrivateSession $session) use ($scheduler, &$count): void {
                if ($scheduler->privateSessionReminder($session) !== null) {
                    $count++;
                }
            });

        return $count;
    }

    private function scheduleSubscriptionExpiryReminders(ScheduleAutomaticNotificationTaskAction $scheduler, int $limit): int
    {
        $count = 0;
        $latestExpiry = now()->addDays(2)->addMinutes(5);

        WorkspaceSubscription::query()
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->whereNotNull('user_id')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', $latestExpiry)
            ->where('remaining_minutes', '>', 0)
            ->whereNotExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('scheduled_notification_tasks')
                    ->whereColumn('scheduled_notification_tasks.entity_id', 'workspace_subscriptions.id')
                    ->where('scheduled_notification_tasks.entity_type', WorkspaceSubscription::class)
                    ->where('scheduled_notification_tasks.type', ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D)
                    ->whereIn('scheduled_notification_tasks.status', [
                        ScheduledNotificationTask::STATUS_PENDING,
                        ScheduledNotificationTask::STATUS_QUEUED,
                        ScheduledNotificationTask::STATUS_PROCESSING,
                        ScheduledNotificationTask::STATUS_PROCESSED,
                    ]);
            })
            ->select(['id', 'workspace_id', 'user_id', 'status', 'expires_at', 'remaining_minutes'])
            ->limit($limit)
            ->lazyById(500)
            ->each(function (WorkspaceSubscription $subscription) use ($scheduler, &$count): void {
                if ($scheduler->workspaceSubscriptionExpiry($subscription) !== null) {
                    $count++;
                }
            });

        return $count;
    }

    private function scheduleSubscriptionLowHoursReminders(ScheduleAutomaticNotificationTaskAction $scheduler, int $limit): int
    {
        $count = 0;

        WorkspaceSubscription::query()
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->whereNotNull('user_id')
            ->where('remaining_minutes', '>', 0)
            ->where('remaining_minutes', '<', 16 * 60)
            ->whereNotExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('scheduled_notification_tasks')
                    ->whereColumn('scheduled_notification_tasks.entity_id', 'workspace_subscriptions.id')
                    ->where('scheduled_notification_tasks.entity_type', WorkspaceSubscription::class)
                    ->where('scheduled_notification_tasks.type', ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H)
                    ->whereIn('scheduled_notification_tasks.status', [
                        ScheduledNotificationTask::STATUS_PENDING,
                        ScheduledNotificationTask::STATUS_QUEUED,
                        ScheduledNotificationTask::STATUS_PROCESSING,
                        ScheduledNotificationTask::STATUS_PROCESSED,
                    ]);
            })
            ->select(['id', 'workspace_id', 'user_id', 'status', 'expires_at', 'remaining_minutes'])
            ->limit($limit)
            ->lazyById(500)
            ->each(function (WorkspaceSubscription $subscription) use ($scheduler, &$count): void {
                if ($scheduler->workspaceSubscriptionLowHours($subscription) !== null) {
                    $count++;
                }
            });

        return $count;
    }

    private function queueDueTasks(int $limit): int
    {
        $queued = 0;

        ScheduledNotificationTask::query()
            ->where('status', ScheduledNotificationTask::STATUS_PENDING)
            ->where('due_at', '<=', now())
            ->orderBy('due_at')
            ->limit($limit)
            ->get(['id'])
            ->each(function (ScheduledNotificationTask $task) use (&$queued): void {
                $updated = ScheduledNotificationTask::query()
                    ->whereKey($task->id)
                    ->where('status', ScheduledNotificationTask::STATUS_PENDING)
                    ->update([
                        'status' => ScheduledNotificationTask::STATUS_QUEUED,
                        'updated_at' => now(),
                    ]);

                if ($updated !== 1) {
                    return;
                }

                ProcessScheduledNotificationTaskJob::dispatch($task->id)
                    ->onQueue((string) config('notification_campaigns.queue', 'notifications'));

                $queued++;
            });

        return $queued;
    }
}
