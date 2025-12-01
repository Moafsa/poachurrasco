<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule periodic establishment synchronization (daily)
Schedule::command('establishments:sync')
    ->daily()
    ->withoutOverlapping()
    ->runInBackground();

// Schedule refresh of existing establishments data (daily at different time)
Schedule::command('establishments:sync --refresh')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();

// Schedule external reviews synchronization (daily)
Schedule::command('reviews:sync-external --limit=100')
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->runInBackground();
