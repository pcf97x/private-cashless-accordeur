<?php

namespace App\Console\Commands;

use App\Models\Checkin;
use Illuminate\Console\Command;

class CloseCheckins extends Command
{
    protected $signature = 'checkins:close {--hour=19 : Heure de fermeture (défaut: 19h)}';
    protected $description = 'Ferme les pointages du jour sans sortie en mettant l\'heure de fin';

    public function handle(): int
    {
        $hour = (int) $this->option('hour');
        $closingTime = now()->setTime($hour, 0, 0);
        $today = now()->toDateString();

        $unclosed = Checkin::whereDate('scan_date', $today)
            ->whereNotNull('entry_at')
            ->whereNull('exit_at')
            ->get();

        $count = 0;
        foreach ($unclosed as $checkin) {
            $checkin->update(['exit_at' => $closingTime]);
            $count++;
        }

        $this->info("$count pointage(s) fermé(s) automatiquement à {$hour}h00.");

        return self::SUCCESS;
    }
}
