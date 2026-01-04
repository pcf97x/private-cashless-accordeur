<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Checkin;
use App\Models\Contact;

class SyncContacts extends Command
{
    protected $signature = 'contacts:sync';
    protected $description = 'Créer et rattacher les contacts depuis les checkins existants';

    public function handle()
    {
        $this->info('🔄 Synchronisation des contacts...');

        Checkin::whereNotNull('email')
            ->whereNull('contact_id')
            ->chunk(100, function ($checkins) {

                foreach ($checkins as $checkin) {

                    $contact = Contact::firstOrCreate(
                        ['email' => $checkin->email],
                        [
                            'firstname' => $checkin->firstname,
                            'lastname'  => $checkin->lastname,
                            'company'   => $checkin->company,
                        ]
                    );

                    $checkin->update([
                        'contact_id' => $contact->id
                    ]);
                }
            });

        $this->info('✅ Synchronisation terminée');
    }
}
