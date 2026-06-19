<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('subscriptions:expire')->daily();
Schedule::command('reports:rollup-visits')->dailyAt('02:00')->onOneServer()->withoutOverlapping();
Schedule::command('workspace-visits:retention')->monthlyOn(1, '03:00')->onOneServer()->withoutOverlapping();
