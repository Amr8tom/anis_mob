<?php

declare(strict_types=1);

namespace App\Domain\Notifications\Services;

use Carbon\CarbonImmutable;
use DateTimeInterface;

final class NotificationSendWindowService
{
    public function resolveScheduledAt(mixed $requestedAt = null): CarbonImmutable
    {
        $timezone = $this->timezone();
        $candidate = $this->parseRequestedAt($requestedAt, $timezone);
        $now = CarbonImmutable::now($timezone);

        if ($candidate->lessThan($now)) {
            $candidate = $now;
        }

        if (! $this->quietHoursEnabled() || ! $this->isQuietTime($candidate)) {
            return $candidate;
        }

        return $this->nextAllowedTime($candidate);
    }

    public function isDue(mixed $scheduledAt): bool
    {
        if ($scheduledAt === null) {
            return true;
        }

        return CarbonImmutable::parse($scheduledAt)->lessThanOrEqualTo(now());
    }

    private function parseRequestedAt(mixed $requestedAt, string $timezone): CarbonImmutable
    {
        if ($requestedAt instanceof DateTimeInterface) {
            return CarbonImmutable::instance($requestedAt)->timezone($timezone);
        }

        if (is_string($requestedAt) && trim($requestedAt) !== '') {
            return CarbonImmutable::parse($requestedAt, $timezone);
        }

        return CarbonImmutable::now($timezone);
    }

    private function quietHoursEnabled(): bool
    {
        return (bool) config('notification_campaigns.quiet_hours.enabled', true);
    }

    private function timezone(): string
    {
        return (string) config('notification_campaigns.quiet_hours.timezone', config('app.timezone', 'Africa/Cairo'));
    }

    private function isQuietTime(CarbonImmutable $time): bool
    {
        [$startHour, $startMinute] = $this->timeParts((string) config('notification_campaigns.quiet_hours.start', '22:00'));
        [$endHour, $endMinute] = $this->timeParts((string) config('notification_campaigns.quiet_hours.end', '08:00'));

        $start = $time->setTime($startHour, $startMinute);
        $end = $time->setTime($endHour, $endMinute);

        if ($start->lessThan($end)) {
            return $time->greaterThanOrEqualTo($start) && $time->lessThan($end);
        }

        return $time->greaterThanOrEqualTo($start) || $time->lessThan($end);
    }

    private function nextAllowedTime(CarbonImmutable $time): CarbonImmutable
    {
        [$startHour, $startMinute] = $this->timeParts((string) config('notification_campaigns.quiet_hours.start', '22:00'));
        [$endHour, $endMinute] = $this->timeParts((string) config('notification_campaigns.quiet_hours.end', '08:00'));

        $start = $time->setTime($startHour, $startMinute);
        $end = $time->setTime($endHour, $endMinute);

        if ($start->lessThan($end)) {
            return $end;
        }

        if ($time->greaterThanOrEqualTo($start)) {
            return $end->addDay();
        }

        return $end;
    }

    /**
     * @return array{0:int,1:int}
     */
    private function timeParts(string $value): array
    {
        $parts = explode(':', $value);

        return [
            max(0, min(23, (int) ($parts[0] ?? 0))),
            max(0, min(59, (int) ($parts[1] ?? 0))),
        ];
    }
}
