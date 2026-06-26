<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Notification campaign queue
    |--------------------------------------------------------------------------
    |
    | Push campaigns are intentionally processed outside the HTTP request. Keep
    | this queue separated so high-volume campaigns do not block normal app jobs.
    |
    */
    'queue' => env('NOTIFICATION_QUEUE', 'notifications'),

    /*
    |--------------------------------------------------------------------------
    | Dispatch batch size
    |--------------------------------------------------------------------------
    |
    | FCM supports multicast-style fanout, but this code stores per-device
    | recipient rows first. Smaller batches keep memory stable on large tenants.
    |
    */
    'batch_size' => (int) env('NOTIFICATION_BATCH_SIZE', 500),

    /*
    |--------------------------------------------------------------------------
    | Manual campaign limits
    |--------------------------------------------------------------------------
    |
    | Workspace owners should not be able to spam the same visitor repeatedly.
    | The per-user limit is intentionally fixed to 5 by default, matching the
    | product rule. The workspace daily limit protects the system from one tenant
    | creating too many campaigns in a single day.
    |
    */
    'workspace_daily_limit' => (int) env('NOTIFICATION_WORKSPACE_DAILY_LIMIT', 50),
    'per_user_daily_limit' => (int) env('NOTIFICATION_PER_USER_DAILY_LIMIT', 5),

    /*
    |--------------------------------------------------------------------------
    | Recipient detail retention
    |--------------------------------------------------------------------------
    |
    | Campaign summary counters are kept forever, but per-device recipient rows
    | are operational detail. Keep them for at most 60 days to avoid unbounded
    | growth when the system reaches millions of notifications.
    |
    */
    'retention_days' => min(60, max(1, (int) env('NOTIFICATION_RECIPIENT_RETENTION_DAYS', 60))),

    /*
    |--------------------------------------------------------------------------
    | Quiet hours
    |--------------------------------------------------------------------------
    |
    | Immediate/manual campaigns created during quiet hours are delayed to the
    | next allowed time. Scheduled campaigns that land inside the window are also
    | shifted forward. Times are interpreted in this timezone.
    |
    */
    'quiet_hours' => [
        'enabled' => filter_var(env('NOTIFICATION_QUIET_HOURS_ENABLED', true), FILTER_VALIDATE_BOOL),
        'timezone' => env('NOTIFICATION_QUIET_HOURS_TIMEZONE', env('APP_TIMEZONE', 'Africa/Cairo')),
        'start' => env('NOTIFICATION_QUIET_HOURS_START', '22:00'),
        'end' => env('NOTIFICATION_QUIET_HOURS_END', '08:00'),
    ],
];
