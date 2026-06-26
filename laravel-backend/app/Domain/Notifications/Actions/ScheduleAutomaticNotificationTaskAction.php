<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Actions;

use App\Enums\SessionStatus;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Models\ScheduledNotificationTask;
use App\Models\StudySession;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspaceSubscription;

final readonly class ScheduleAutomaticNotificationTaskAction
{
    public function __construct(private UpsertScheduledNotificationTaskAction $upsertTask)
    {
    }

    public function publicSessionReminder(StudySession $session): ?ScheduledNotificationTask
    {
        if ($session->start_time === null || $session->start_time->isPast()) {
            return null;
        }

        if ($session->status !== SessionStatus::UPCOMING) {
            return null;
        }

        return $this->upsertTask->execute(
            ScheduledNotificationTask::TYPE_PUBLIC_SESSION_18H,
            StudySession::class,
            $session->id,
            $session->start_time->copy()->subHours(18),
            ScheduledNotificationTask::TYPE_PUBLIC_SESSION_18H.':'.$session->id,
            $session->workspace_id,
            ['session_id' => $session->id],
        );
    }

    public function privateSessionReminder(WorkspacePrivateSession $session): ?ScheduledNotificationTask
    {
        if ($session->starts_at === null || $session->starts_at->isPast()) {
            return null;
        }

        if ($session->status !== 'active') {
            return null;
        }

        return $this->upsertTask->execute(
            ScheduledNotificationTask::TYPE_PRIVATE_SESSION_18H,
            WorkspacePrivateSession::class,
            $session->id,
            $session->starts_at->copy()->subHours(18),
            ScheduledNotificationTask::TYPE_PRIVATE_SESSION_18H.':'.$session->id,
            $session->workspace_id,
            ['session_id' => $session->id],
        );
    }

    public function workspaceSubscriptionExpiry(WorkspaceSubscription $subscription): ?ScheduledNotificationTask
    {
        if (
            $subscription->status !== WorkspaceSubscriptionStatus::ACTIVE
            || $subscription->user_id === null
            || $subscription->expires_at === null
            || $subscription->expires_at->isPast()
            || $subscription->remaining_minutes <= 0
        ) {
            return null;
        }

        return $this->upsertTask->execute(
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D,
            WorkspaceSubscription::class,
            $subscription->id,
            $subscription->expires_at->copy()->subDays(2),
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_EXPIRY_2D.':'.$subscription->id,
            $subscription->workspace_id,
            ['subscription_id' => $subscription->id],
        );
    }

    public function workspaceSubscriptionLowHours(WorkspaceSubscription $subscription): ?ScheduledNotificationTask
    {
        if (
            $subscription->status !== WorkspaceSubscriptionStatus::ACTIVE
            || $subscription->user_id === null
            || $subscription->remaining_minutes <= 0
            || $subscription->remaining_minutes >= 16 * 60
        ) {
            return null;
        }

        return $this->upsertTask->execute(
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H,
            WorkspaceSubscription::class,
            $subscription->id,
            now(),
            ScheduledNotificationTask::TYPE_WORKSPACE_SUBSCRIPTION_LOW_HOURS_16H.':'.$subscription->id,
            $subscription->workspace_id,
            ['subscription_id' => $subscription->id],
        );
    }
}
