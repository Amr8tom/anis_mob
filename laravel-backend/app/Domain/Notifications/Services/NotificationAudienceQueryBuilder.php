<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Services;

use App\Models\DeviceToken;
use App\Models\NotificationCampaign;
use App\Models\UserNotificationPreference;
use Illuminate\Database\Eloquent\Builder;

final class NotificationAudienceQueryBuilder
{
    /** @return Builder<DeviceToken> */
    public function deviceTokensForCampaign(NotificationCampaign $campaign): Builder
    {
        $query = DeviceToken::query()
            ->where('is_active', true)
            ->whereNotNull('user_id')
            ->whereExists(function ($subquery): void {
                $subquery->selectRaw('1')
                    ->from('users')
                    ->whereColumn('users.id', 'device_tokens.user_id')
                    ->whereNull('users.deleted_at');
            });

        $payload = $campaign->target_payload ?? [];

        $query = match ($campaign->target_type) {
            'user', 'selected' => $this->forSelectedUsers($query, $payload, $campaign->workspace_id),
            'all_visitors', 'workspace_visitors' => $this->forWorkspaceVisitors($query, $payload, $campaign->workspace_id),
            'public_session' => $this->forPublicSession($query, $payload, $campaign->workspace_id),
            'private_session' => $this->forPrivateSession($query, $payload, $campaign->workspace_id),
            'workspace_subscription' => $this->forWorkspaceSubscription($query, $payload, $campaign->workspace_id),
            'all_users' => $query,
            default => $query->whereRaw('1 = 0'),
        };

        return $this->whereUserAllowsCategory(
            $query,
            UserNotificationPreference::normalizeCategory($campaign->notification_category),
        );
    }

    /** @param Builder<DeviceToken> $query */
    private function forSelectedUsers(Builder $query, array $payload, ?string $workspaceId): Builder
    {
        $userIds = collect($payload['user_ids'] ?? [])
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($userIds === []) {
            return $query->whereRaw('1 = 0');
        }

        $query->whereIn('device_tokens.user_id', $userIds);

        if ($workspaceId !== null) {
            $this->whereWorkspaceVisitor($query, $workspaceId);
        }

        return $query;
    }

    /** @param Builder<DeviceToken> $query */
    private function forWorkspaceVisitors(Builder $query, array $payload, ?string $workspaceId): Builder
    {
        $workspaceId ??= $payload['workspace_id'] ?? null;

        if (! is_string($workspaceId) || $workspaceId === '') {
            return $query->whereRaw('1 = 0');
        }

        return $this->whereWorkspaceVisitor($query, $workspaceId);
    }

    /** @param Builder<DeviceToken> $query */
    private function forPublicSession(Builder $query, array $payload, ?string $workspaceId): Builder
    {
        $sessionId = $payload['session_id'] ?? null;

        if (! is_string($sessionId) || $sessionId === '') {
            return $query->whereRaw('1 = 0');
        }

        $query->whereExists(function ($subquery) use ($sessionId): void {
            $subquery->selectRaw('1')
                ->from('session_participants')
                ->whereColumn('session_participants.user_id', 'device_tokens.user_id')
                ->where('session_participants.session_id', $sessionId);
        });

        if ($workspaceId !== null) {
            $query->whereExists(function ($subquery) use ($sessionId, $workspaceId): void {
                $subquery->selectRaw('1')
                    ->from('study_sessions')
                    ->where('study_sessions.id', $sessionId)
                    ->where('study_sessions.workspace_id', $workspaceId);
            });
        }

        return $query;
    }

    /** @param Builder<DeviceToken> $query */
    private function forPrivateSession(Builder $query, array $payload, ?string $workspaceId): Builder
    {
        $sessionId = $payload['session_id'] ?? null;

        if (! is_string($sessionId) || $sessionId === '') {
            return $query->whereRaw('1 = 0');
        }

        $query->whereExists(function ($subquery) use ($sessionId): void {
            $subquery->selectRaw('1')
                ->from('workspace_private_session_attendees')
                ->whereColumn('workspace_private_session_attendees.user_id', 'device_tokens.user_id')
                ->where('workspace_private_session_attendees.workspace_private_session_id', $sessionId);
        });

        if ($workspaceId !== null) {
            $query->whereExists(function ($subquery) use ($sessionId, $workspaceId): void {
                $subquery->selectRaw('1')
                    ->from('workspace_private_sessions')
                    ->where('workspace_private_sessions.id', $sessionId)
                    ->where('workspace_private_sessions.workspace_id', $workspaceId);
            });
        }

        return $query;
    }

    /** @param Builder<DeviceToken> $query */
    private function whereWorkspaceVisitor(Builder $query, string $workspaceId): Builder
    {
        return $query->whereExists(function ($subquery) use ($workspaceId): void {
            $subquery->selectRaw('1')
                ->from('workspace_visits')
                ->whereColumn('workspace_visits.user_id', 'device_tokens.user_id')
                ->where('workspace_visits.workspace_id', $workspaceId);
        });
    }

    /** @param Builder<DeviceToken> $query */
    private function forWorkspaceSubscription(Builder $query, array $payload, ?string $workspaceId): Builder
    {
        $subscriptionId = $payload['subscription_id'] ?? null;

        if (! is_string($subscriptionId) || $subscriptionId === '') {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereExists(function ($subquery) use ($subscriptionId, $workspaceId): void {
            $subquery->selectRaw('1')
                ->from('workspace_subscriptions')
                ->whereColumn('workspace_subscriptions.user_id', 'device_tokens.user_id')
                ->where('workspace_subscriptions.id', $subscriptionId)
                ->where('workspace_subscriptions.status', 'ACTIVE')
                ->where('workspace_subscriptions.remaining_minutes', '>', 0)
                ->when($workspaceId !== null, fn ($query) => $query->where('workspace_subscriptions.workspace_id', $workspaceId));
        });
    }

    /** @param Builder<DeviceToken> $query */
    private function whereUserAllowsCategory(Builder $query, string $category): Builder
    {
        return $query->where(function (Builder $scope) use ($category): void {
            $scope
                ->whereNotExists(function ($subquery): void {
                    $subquery->selectRaw('1')
                        ->from('user_notification_preferences')
                        ->whereColumn('user_notification_preferences.user_id', 'device_tokens.user_id');
                })
                ->orWhereExists(function ($subquery) use ($category): void {
                    $subquery->selectRaw('1')
                        ->from('user_notification_preferences')
                        ->whereColumn('user_notification_preferences.user_id', 'device_tokens.user_id')
                        ->where("user_notification_preferences.{$category}", true);
                });
        });
    }
}
