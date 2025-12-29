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

            // ⛔ Pas de scan valide
            if (
                empty($p['barcode']) ||
                empty($p['control_status']['date_scan']) ||
                $p['control_status']['date_scan'] === '0000-00-00 00:00:00'
            ) {
                continue;
            }

            // 🕒 Weezevent = UTC → Guyane
            $scanAt = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $p['control_status']['date_scan'],
                'UTC'
            )->setTimezone('America/Cayenne');

            $scanDate = $scanAt->toDateString();

            // 🔒 1 billet / jour
            $checkin = Checkin::firstOrCreate(
                [
                    'weez_ticket_code' => $p['barcode'],
                    'scan_date'        => $scanDate,
                ],
                [
                    'weez_participant_id' => $p['id_participant'],
                    'weez_event_id'       => $p['id_evenement'],
                    'entry_at'            => $scanAt,
                ]
            );

            // 🔁 Scan suivant = sortie
            if ($checkin->entry_at && !$checkin->exit_at && $scanAt->gt($checkin->entry_at)) {
                $checkin->update([
                    'exit_at' => $scanAt
                ]);
            }
        }

        $this->info('Sync Weezevent terminée');
    }
}
