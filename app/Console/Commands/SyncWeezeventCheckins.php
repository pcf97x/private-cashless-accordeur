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
            $firstname = $p['prenom'] ?: ($p['owner']['acheteur_prenom'] ?? '');
            $lastname = $p['nom'] ?: ($p['owner']['acheteur_nom'] ?? '');
            $email = $p['email'] ?: ($p['owner']['acheteur_email'] ?? '');

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

            // Chercher le record original pour récupérer les infos (contact_id, purpose, etc.)
            $original = Checkin::where('weez_ticket_code', $p['barcode'])
                ->whereNotNull('firstname')
                ->where('firstname', '!=', '')
                ->first();

            if ($checkin) {
                // Mise à jour de la fiche existante
                $updateData = [
                    'weez_event_id' => $p['id_evenement'],
                    'weez_participant_id' => $p['id_participant'],
                ];

                // Remplir le nom/email si manquant
                if (empty($checkin->firstname)) {
                    $updateData['firstname'] = $firstname ?: ($original->firstname ?? '');
                    $updateData['lastname'] = $lastname ?: ($original->lastname ?? '');
                    $updateData['email'] = $email ?: ($original->email ?? '');
                    $updateData['contact_id'] = $original->contact_id ?? $checkin->contact_id;
                    $updateData['purpose'] = $original->purpose ?? $checkin->purpose;
                    $updateData['company'] = $original->company ?? $checkin->company;
                }

                if (!$checkin->entry_at) {
                    $updateData['scan_date'] = $scanDate;
                    $updateData['entry_at'] = $scanAt;
                } elseif (!$checkin->exit_at && $scanAt->gt($checkin->entry_at)) {
                    $updateData['exit_at'] = $scanAt;
                }

                $checkin->update($updateData);
            } else {
                // Nouveau record (nouveau jour ou inconnu)
                // Créer/rattacher un contact si on a un email
                $contactId = $original->contact_id ?? null;
                if (!$contactId && $email) {
                    $contact = Contact::firstOrCreate(
                        ['email' => $email],
                        ['firstname' => $firstname, 'lastname' => $lastname]
                    );
                    $contactId = $contact->id;
                }

                Checkin::create([
                    'weez_ticket_code'    => $p['barcode'],
                    'weez_event_id'       => $p['id_evenement'],
                    'weez_participant_id' => $p['id_participant'],
                    'scan_date'           => $scanDate,
                    'entry_at'            => $scanAt,
                    'firstname'           => $firstname ?: ($original->firstname ?? ''),
                    'lastname'            => $lastname ?: ($original->lastname ?? ''),
                    'email'               => $email ?: ($original->email ?? ''),
                    'contact_id'          => $contactId,
                    'purpose'             => $original->purpose ?? null,
                    'company'             => $original->company ?? null,
                    'qr_token'            => $original->qr_token ?? null,
                ]);
            }
        }

        $this->info('Sync Weezevent terminée');
    }
}
