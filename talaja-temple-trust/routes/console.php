<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// M7: Daily database + storage backup at 2 AM.
Schedule::command('app:backup-database')->dailyAt('02:00')->withoutOverlapping()->onOneServer();
