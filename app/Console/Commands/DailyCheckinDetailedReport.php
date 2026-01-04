<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Checkin;
use Carbon\Carbon;

class DailyCheckinDetailedReport extends Command
{
    protected $signature = 'report:daily-checkins-detailed {date?}';
    protected $description = 'Rapport journalier détaillé des entrées/sorties';

    public function handle()
    {
        $date = $this->argument('date') ?? Carbon::today()->toDateString();

        $this->info("📅 Rapport journalier – {$date}");
        $this->line(str_repeat('-', 110));

        $checkins = Checkin::where('scan_date', $date)
            ->orderBy('entry_at')
            ->get();

        if ($checkins->isEmpty()) {
            $this->warn('⚠️ Aucun pointage trouvé pour cette date');
            return Command::SUCCESS;
        }

        foreach ($checkins as $c) {
            $this->line(sprintf(
                "%-15s %-15s | %-20s | %-15s | Entrée: %-8s | Sortie: %-8s",
                $c->firstname ?? '-',
                $c->lastname ?? '-',
                $c->company ?? '-',      // ✅ champ optionnel
                $c->purpose ?? '-',
                $c->entry_at ? $c->entry_at->format('H:i') : '-',
                $c->exit_at ? $c->exit_at->format('H:i') : '-'
            ));
        }

        $this->line(str_repeat('-', 110));
        $this->info('✅ Rapport généré avec succès');

        return Command::SUCCESS;
    }
}
