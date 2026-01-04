<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeezeventCheckinService;
use App\Models\Checkin;
use Carbon\Carbon;

class SyncWeezeventCheckins extends Command
{
    protected $signature = 'sync:weezevent-checkins';
    protected $description = 'Synchronise les scans Weezevent';

    public function handle(WeezeventCheckinService $service)
    {
        $eventId = config('services.weezevent.event_id');
        $participants = $service->fetchParticipants($eventId);

        foreach ($participants as $p) {

            if (
                empty($p['barcode']) ||
                empty($p['control_status']['date_scan']) ||
                $p['control_status']['date_scan'] === '0000-00-00 00:00:00'
            ) {
                continue;
            }

            // Weezevent = UTC → Guyane
            $scanAt = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $p['control_status']['date_scan'],
                'UTC'
            )->setTimezone('America/Cayenne');

            $scanDate = $scanAt->toDateString();

            // 🔎 ON RATTACHE AU DOSSIER EXISTANT SI POSSIBLE
            $checkin = Checkin::where('weez_ticket_code', $p['barcode'])
                ->where('scan_date', $scanDate)
                ->first();

            if (!$checkin) {
                // 🔎 Tentative de rattachement à une fiche créée via /acces
                $checkin = Checkin::where('weez_ticket_code', $p['barcode'])
                    ->whereNull('scan_date')
                    ->first();
            }

            if ($checkin) {
                // 🟢 Mise à jour de la fiche existante
                if (!$checkin->entry_at) {
                    $checkin->update([
                        'scan_date' => $scanDate,
                        'entry_at'  => $scanAt,
                        'weez_event_id' => $p['id_evenement'],
                        'weez_participant_id' => $p['id_participant'],
                    ]);
                } elseif (!$checkin->exit_at && $scanAt->gt($checkin->entry_at)) {
                    $checkin->update([
                        'exit_at' => $scanAt
                    ]);
                }
            } else {
                // 🆕 Cas inconnu → création brute
                Checkin::create([
                    'weez_ticket_code'    => $p['barcode'],
                    'weez_event_id'       => $p['id_evenement'],
                    'weez_participant_id' => $p['id_participant'],
                    'scan_date'           => $scanDate,
                    'entry_at'            => $scanAt,
                ]);
            }
        }

        $this->info('Sync Weezevent terminée');
    }
}
