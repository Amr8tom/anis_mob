<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Services;

use App\Models\Workspace;
use App\Models\UserNotificationPreference;
use Illuminate\Support\Facades\DB;

final class NotificationAudiencePreviewService
{
    /**
     * @param array<string, mixed> $payload
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    public function forWorkspace(Workspace $workspace, string $targetType, array $payload = []): array
    {
        $category = UserNotificationPreference::normalizeCategory($payload['notification_category'] ?? null);

        return match ($targetType) {
            'user', 'selected' => $this->selectedWorkspaceUsers($workspace->id, $payload['user_ids'] ?? [], $category),
            'all_visitors' => $this->workspaceVisitors($workspace->id, $category),
            'public_session' => $this->publicSession((string) ($payload['session_id'] ?? ''), $workspace->id, $category),
            'private_session' => $this->privateSession((string) ($payload['session_id'] ?? ''), $workspace->id, $category),
            default => $this->emptyPreview(),
        };
    }

    /**
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    public function forAdmin(string $targetType, ?string $workspaceId = null, ?string $category = null): array
    {
        $category = UserNotificationPreference::normalizeCategory($category);

        return match ($targetType) {
            'all_users' => $this->allAppUsers($category),
            'workspace_visitors' => is_string($workspaceId) && $workspaceId !== ''
                ? $this->workspaceVisitors($workspaceId, $category)
                : $this->emptyPreview(),
            default => $this->emptyPreview(),
        };
    }

    /**
     * @param mixed $userIds
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    private function selectedWorkspaceUsers(string $workspaceId, mixed $userIds, string $category): array
    {
        $ids = collect(is_array($userIds) ? $userIds : [])
            ->filter(fn ($id) => is_string($id) && $id !== '')
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return $this->emptyPreview();
        }

        $registeredUsers = DB::table('users')
            ->whereIn('users.id', $ids)
            ->whereNull('users.deleted_at')
            ->whereExists(function ($query) use ($workspaceId): void {
                $query->selectRaw('1')
                    ->from('workspace_visits')
                    ->whereColumn('workspace_visits.user_id', 'users.id')
                    ->where('workspace_visits.workspace_id', $workspaceId);
            });

        return $this->fromRegisteredUsersQuery($registeredUsers, (clone $registeredUsers)->count(), null, $category);
    }

    /**
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    private function workspaceVisitors(string $workspaceId, string $category): array
    {
        $registeredUsersCount = (int) DB::table('workspace_visits')
            ->where('workspace_id', $workspaceId)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $walkInsCount = (int) DB::table('workspace_visits')
            ->where('workspace_id', $workspaceId)
            ->whereNotNull('walk_in_id')
            ->distinct()
            ->count('walk_in_id');

        $registeredUsers = DB::table('users')
            ->whereNull('users.deleted_at')
            ->whereExists(function ($query) use ($workspaceId): void {
                $query->selectRaw('1')
                    ->from('workspace_visits')
                    ->whereColumn('workspace_visits.user_id', 'users.id')
                    ->where('workspace_visits.workspace_id', $workspaceId);
            });

        return $this->fromRegisteredUsersQuery($registeredUsers, $registeredUsersCount + $walkInsCount, $registeredUsersCount, $category);
    }

    /**
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    private function publicSession(string $sessionId, string $workspaceId, string $category): array
    {
        if ($sessionId === '') {
            return $this->emptyPreview();
        }

        $sessionExists = DB::table('study_sessions')
            ->where('id', $sessionId)
            ->where('workspace_id', $workspaceId)
            ->exists();

        if (! $sessionExists) {
            return $this->emptyPreview();
        }

        $registeredUsersCount = (int) DB::table('session_participants')
            ->where('session_id', $sessionId)
            ->distinct()
            ->count('user_id');

        $registeredUsers = DB::table('users')
            ->whereNull('users.deleted_at')
            ->whereExists(function ($query) use ($sessionId): void {
                $query->selectRaw('1')
                    ->from('session_participants')
                    ->whereColumn('session_participants.user_id', 'users.id')
                    ->where('session_participants.session_id', $sessionId);
            });

        return $this->fromRegisteredUsersQuery($registeredUsers, $registeredUsersCount, $registeredUsersCount, $category);
    }

    /**
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    private function privateSession(string $sessionId, string $workspaceId, string $category): array
    {
        if ($sessionId === '') {
            return $this->emptyPreview();
        }

        $sessionExists = DB::table('workspace_private_sessions')
            ->where('id', $sessionId)
            ->where('workspace_id', $workspaceId)
            ->exists();

        if (! $sessionExists) {
            return $this->emptyPreview();
        }

        $audienceCount = (int) DB::table('workspace_private_session_attendees')
            ->where('workspace_private_session_id', $sessionId)
            ->count();

        $registeredUsersCount = (int) DB::table('workspace_private_session_attendees')
            ->where('workspace_private_session_id', $sessionId)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $registeredUsers = DB::table('users')
            ->whereNull('users.deleted_at')
            ->whereExists(function ($query) use ($sessionId): void {
                $query->selectRaw('1')
                    ->from('workspace_private_session_attendees')
                    ->whereColumn('workspace_private_session_attendees.user_id', 'users.id')
                    ->where('workspace_private_session_attendees.workspace_private_session_id', $sessionId);
            });

        return $this->fromRegisteredUsersQuery($registeredUsers, $audienceCount, $registeredUsersCount, $category);
    }

    /**
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    private function allAppUsers(string $category): array
    {
        $registeredUsers = DB::table('users')->whereNull('users.deleted_at');

        return $this->fromRegisteredUsersQuery($registeredUsers, (clone $registeredUsers)->count(), null, $category);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $registeredUsers
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    private function fromRegisteredUsersQuery($registeredUsers, int $audienceCount, ?int $registeredUsersCount = null, string $category = UserNotificationPreference::CATEGORY_WORKSPACE_UPDATES): array
    {
        $registeredUsersCount ??= (int) (clone $registeredUsers)->count();

        $reachableUsersCount = (int) DB::table('users')
            ->whereIn('users.id', (clone $registeredUsers)->select('users.id'))
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('device_tokens')
                    ->whereColumn('device_tokens.user_id', 'users.id')
                    ->where('device_tokens.is_active', true);
            })
            ->where($this->preferenceAllowedCallback($category, 'users.id'))
            ->count();

        $deviceTokensCount = (int) DB::table('device_tokens')
            ->where('is_active', true)
            ->whereIn('user_id', (clone $registeredUsers)->select('users.id'))
            ->where($this->preferenceAllowedCallback($category, 'device_tokens.user_id'))
            ->count();

        return [
            'audience_count' => $audienceCount,
            'registered_users_count' => $registeredUsersCount,
            'reachable_users_count' => $reachableUsersCount,
            'device_tokens_count' => $deviceTokensCount,
            'skipped_count' => max(0, $audienceCount - $reachableUsersCount),
        ];
    }

    /**
     * @return array{audience_count:int,registered_users_count:int,reachable_users_count:int,device_tokens_count:int,skipped_count:int}
     */
    private function emptyPreview(): array
    {
        return [
            'audience_count' => 0,
            'registered_users_count' => 0,
            'reachable_users_count' => 0,
            'device_tokens_count' => 0,
            'skipped_count' => 0,
        ];
    }

    private function preferenceAllowedCallback(string $category, string $userColumn): callable
    {
        $category = UserNotificationPreference::normalizeCategory($category);

        return function ($query) use ($category, $userColumn): void {
            $query
                ->whereNotExists(function ($subquery) use ($userColumn): void {
                    $subquery->selectRaw('1')
                        ->from('user_notification_preferences')
                        ->whereColumn('user_notification_preferences.user_id', $userColumn);
                })
                ->orWhereExists(function ($subquery) use ($category, $userColumn): void {
                    $subquery->selectRaw('1')
                        ->from('user_notification_preferences')
                        ->whereColumn('user_notification_preferences.user_id', $userColumn)
                        ->where("user_notification_preferences.{$category}", true);
                });
        };
    }
}
