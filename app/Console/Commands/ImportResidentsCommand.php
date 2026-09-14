<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Checkin;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportResidentsCommand extends Command
{
    protected $signature = 'import:residents {file : Path to the Excel file}';
    protected $description = 'Import residents from Excel into contacts and generate access checkins';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, false);

        $header = null;
        $imported = 0;
        $skipped = 0;
        $currentStructure = '';

        // Find header row
        foreach ($data as $i => $row) {
            $first = strtolower(trim((string) ($row[0] ?? '')));
            if ($first === 'structure') {
                $header = $i;
                break;
            }
        }

        if ($header === null) {
            $this->error('Header row with STRUCTURE not found');
            return 1;
        }

        // Column mapping: STRUCTURE(0), N°(1), PRÉNOM(2), NOM(3), POSTE(4), 3CX(5), PORTABLE(6), MAIL(7)
        foreach ($data as $i => $row) {
            if ($i <= $header) continue;

            $structure = trim((string) ($row[0] ?? ''));
            $firstname = trim((string) ($row[2] ?? ''));
            $lastname = trim((string) ($row[3] ?? ''));
            $poste = trim((string) ($row[4] ?? ''));
            $phone = trim((string) ($row[6] ?? ''));
            $email = trim((string) ($row[7] ?? ''));

            // Track current structure
            if ($structure) $currentStructure = $structure;

            // Skip section headers (like "RÉSIDENTS L'ACCORDEUR")
            if (!$firstname && !$lastname && !$email) continue;

            // Clean phone
            $phone = preg_replace('/\s+/', '', $phone);

            // Clean email (take first if multiple)
            if (str_contains($email, "\n")) {
                $email = trim(explode("\n", $email)[0]);
            }

            // Skip if no useful data
            if (!$firstname && !$lastname && !$email) {
                $skipped++;
                continue;
            }

            $name = trim("$firstname $lastname");

            // Create/update contact
            $contact = null;
            if ($email) {
                $contact = Contact::firstOrCreate(
                    ['email' => $email],
                    [
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'company' => $currentStructure,
                        'phone' => $phone,
                    ]
                );

                // Update if existing but missing info
                $contact->update(array_filter([
                    'firstname' => $contact->firstname ?: $firstname,
                    'lastname' => $contact->lastname ?: $lastname,
                    'company' => $currentStructure ?: $contact->company,
                    'phone' => $phone ?: $contact->phone,
                ]));
            }

            // Check if checkin already exists for this contact
            $existingCheckin = $contact
                ? Checkin::where('contact_id', $contact->id)->first()
                : null;

            if (!$existingCheckin) {
                Checkin::create([
                    'contact_id' => $contact?->id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'company' => $currentStructure,
                    'email' => $email ?: null,
                    'purpose' => 'Résident - ' . $currentStructure . ($poste ? " ($poste)" : ''),
                    'qr_token' => (string) Str::uuid(),
                ]);

                $this->line("+ $name ($currentStructure) — $email");
                $imported++;
            } else {
                $this->line("  skip $name — checkin exists");
                $skipped++;
            }
        }

        $this->info("Done: $imported imported, $skipped skipped.");
        return 0;
    }
}
