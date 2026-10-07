<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sync Weezevent checkins toutes les 10 secondes
Schedule::command('sync:weezevent-checkins')
    ->everyTenSeconds()
    ->withoutOverlapping()
    ->runInBackground();

// Rapport hebdomadaire chaque lundi à 7h (si activé dans les paramètres)
Schedule::call(function () {
    if (\App\Models\Setting::get('weekly_report_enabled', '0') === '1') {
        \Illuminate\Support\Facades\Artisan::call('report:weekly');
    }
})->weeklyOn(1, '07:00'); // Monday 7:00 AM

// Fermer automatiquement les pointages sans sortie à 19h
Schedule::command('checkins:close --hour=19')
    ->dailyAt('19:00')
    ->appendOutputTo(storage_path('logs/checkins-close.log'));
