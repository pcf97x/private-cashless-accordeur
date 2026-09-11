<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeezeventCheckinService;
use App\Models\Checkin;
use App\Models\Contact;
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

            // Extraire les infos du participant depuis l'API Weezevent
            $weezFirstname = $p['prenom'] ?: ($p['owner']['acheteur_prenom'] ?? '');
            $weezLastname = $p['nom'] ?: ($p['owner']['acheteur_nom'] ?? '');
            $weezEmail = $p['email'] ?: ($p['owner']['acheteur_email'] ?? '');

            // Chercher le Contact existant par email (source de vérité pour le nom)
            $contact = null;
            if ($weezEmail) {
                $contact = Contact::where('email', $weezEmail)->first();
            }

            // Le Contact prime sur Weezevent pour le nom
            $firstname = $contact->firstname ?? $weezFirstname;
            $lastname = $contact->lastname ?? $weezLastname;
            $email = $contact->email ?? $weezEmail;
            $contactId = $contact->id ?? null;

            // Si pas de Contact mais on a un email, en créer un
            if (!$contact && $email) {
                $contact = Contact::create([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                ]);
                $contactId = $contact->id;
            }

            // Chercher le record original pour les infos complémentaires (purpose, company, qr_token)
            $original = Checkin::where('weez_ticket_code', $p['barcode'])
                ->whereNotNull('purpose')
                ->first();

            // Chercher un record existant pour ce barcode + cette date
            $checkin = Checkin::where('weez_ticket_code', $p['barcode'])
                ->whereDate('scan_date', $scanDate)
                ->first();

            if (!$checkin) {
                // Chercher un record sans scan_date (créé via /acces ou réservation)
                $checkin = Checkin::where('weez_ticket_code', $p['barcode'])
                    ->whereNull('scan_date')
                    ->first();
            }

            if ($checkin) {
                // Mise à jour de la fiche existante
                $updateData = [
                    'weez_event_id' => $p['id_evenement'],
                    'weez_participant_id' => $p['id_participant'],
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'contact_id' => $contactId ?? $checkin->contact_id,
                ];

                if (!$checkin->entry_at) {
                    $updateData['scan_date'] = $scanDate;
                    $updateData['entry_at'] = $scanAt;
                } elseif (!$checkin->exit_at && $scanAt->gt($checkin->entry_at)) {
                    $updateData['exit_at'] = $scanAt;
                }

                $checkin->update($updateData);
            } else {
                // Nouveau record (nouveau jour ou inconnu)
                Checkin::create([
                    'weez_ticket_code'    => $p['barcode'],
                    'weez_event_id'       => $p['id_evenement'],
                    'weez_participant_id' => $p['id_participant'],
                    'scan_date'           => $scanDate,
                    'entry_at'            => $scanAt,
                    'firstname'           => $firstname,
                    'lastname'            => $lastname,
                    'email'               => $email,
                    'contact_id'          => $contactId,
                    'purpose'             => $original->purpose ?? null,
                    'company'             => $original->company ?? $contact->company ?? null,
                    'qr_token'            => $original->qr_token ?? null,
                ]);
            }
        }

        $this->info('Sync Weezevent terminée');
    }
}
