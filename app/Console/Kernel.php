<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('sync:weezevent-checkins')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/weezevent-sync.log'));

        // Fermer automatiquement les pointages sans sortie à 19h
        $schedule->command('checkins:close --hour=19')
            ->dailyAt('19:00')
            ->appendOutputTo(storage_path('logs/checkins-close.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
    \App\Console\Commands\SyncContacts::class,
];
}
