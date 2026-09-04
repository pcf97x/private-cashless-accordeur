<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sync Weezevent checkins toutes les 5 minutes
Schedule::command('sync:weezevent-checkins')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/weezevent-sync.log'));

// Fermer automatiquement les pointages sans sortie à 19h
Schedule::command('checkins:close --hour=19')
    ->dailyAt('19:00')
    ->appendOutputTo(storage_path('logs/checkins-close.log'));
