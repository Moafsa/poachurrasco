<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule periodic establishment synchronization
Schedule::command('establishments:sync')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

// Schedule refresh of existing establishments data
Schedule::command('establishments:sync --refresh')
    ->daily()
    ->withoutOverlapping()
    ->runInBackground();
