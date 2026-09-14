<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Checkin;
use App\Mail\AccessConfirmed;
use App\Services\WeezeventParticipantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::withCount('checkins')
            ->with([
                'checkins' => function ($q) {
                    $q->orderByDesc('entry_at');
                }
            ])
            ->orderBy('lastname')
            ->get()
            ->map(function ($contact) {
                $lastCheckin = $contact->checkins->first();

                $contact->last_qr_token = $lastCheckin?->qr_token;
                $contact->last_entry_at = $lastCheckin?->entry_at;

                return $contact;
            });

        return view('admin.contacts.index', compact('contacts'));
    }
 
 
 
 public function show(Contact $contact)
{
    $checkins = $contact->checkins()
        ->orderByDesc('entry_at')
        ->get();

    return view('admin.contacts.show', [
        'contact'  => $contact,
        'checkins' => $checkins,
    ]);
}

public function importForm()
{
    return view('admin.contacts.import');
}

public function import(Request $request, WeezeventParticipantService $weezevent)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,txt,xlsx,xls',
    ]);

    $file = $request->file('file');
    $extension = strtolower($file->getClientOriginalExtension());

    $rows = [];

    if (in_array($extension, ['csv', 'txt'])) {
        $handle = fopen($file->getRealPath(), 'r');
        $header = null;
        while (($line = fgetcsv($handle, 0, ';')) !== false) {
            if (!$header) {
                $line[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $line[0]);
                $header = array_map('strtolower', array_map('trim', $line));
                continue;
            }
            if (count($line) === count($header)) {
                $rows[] = array_combine($header, $line);
            }
        }
        fclose($handle);
    } elseif (in_array($extension, ['xlsx', 'xls'])) {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, true, true, false);

            $header = null;
            foreach ($data as $line) {
                if (!$header) {
                    $header = array_map('strtolower', array_map('trim', array_map('strval', $line)));
                    continue;
                }
                if (count($line) === count($header)) {
                    $values = array_map(fn($v) => trim((string) ($v ?? '')), $line);
                    $row = array_combine($header, $values);
                    if (array_filter($row)) {
                        $rows[] = $row;
                    }
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur de lecture : ' . $e->getMessage());
        }
    }

    if (empty($rows)) {
        return back()->with('error', 'Fichier vide ou format non reconnu.');
    }

    $imported = 0;
    $skipped = 0;
    $errors = [];
    $doWeezevent = $request->boolean('create_weezevent', true);
    $doEmail = $request->boolean('send_email', false);

    foreach ($rows as $i => $row) {
        $lineNum = $i + 2;

        $firstname = trim($row['prenom'] ?? $row['prénom'] ?? $row['firstname'] ?? '');
        $lastname = trim($row['nom'] ?? $row['lastname'] ?? '');
        $email = trim($row['email'] ?? $row['mail'] ?? '');
        $phone = trim($row['telephone'] ?? $row['tel'] ?? $row['portable'] ?? $row['phone'] ?? '');
        $company = trim($row['structure'] ?? $row['societe'] ?? $row['société'] ?? $row['company'] ?? '');
        $poste = trim($row['poste'] ?? $row['fonction'] ?? '');

        if (!$firstname && !$lastname) {
            $skipped++;
            continue;
        }

        $phone = preg_replace('/\s+/', '', $phone);
        if (str_contains($email, "\n")) {
            $email = trim(explode("\n", $email)[0]);
        }

        // Contact
        $contact = null;
        if ($email) {
            $contact = Contact::firstOrCreate(
                ['email' => $email],
                ['firstname' => $firstname, 'lastname' => $lastname, 'company' => $company, 'phone' => $phone]
            );
            $contact->update(array_filter([
                'firstname' => $contact->firstname ?: $firstname,
                'lastname' => $contact->lastname ?: $lastname,
                'company' => $company ?: $contact->company,
                'phone' => $phone ?: $contact->phone,
            ]));
        } else {
            $contact = Contact::create([
                'firstname' => $firstname, 'lastname' => $lastname,
                'company' => $company, 'phone' => $phone, 'email' => '',
            ]);
        }

        // Skip if checkin exists
        if (Checkin::where('contact_id', $contact->id)->exists()) {
            $skipped++;
            continue;
        }

        $purpose = $company ? "Resident - $company" : 'Contact importe';
        if ($poste) $purpose .= " ($poste)";

        $checkin = Checkin::create([
            'contact_id' => $contact->id,
            'firstname' => $firstname, 'lastname' => $lastname,
            'company' => $company, 'email' => $email ?: null,
            'purpose' => $purpose, 'qr_token' => (string) Str::uuid(),
        ]);

        // Weezevent
        if ($doWeezevent && $email) {
            try {
                $response = $weezevent->createParticipant([
                    'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email,
                ]);
                $participant = $response['participants'][0] ?? null;
                if ($participant) {
                    $checkin->update([
                        'weez_participant_id' => $participant['id_participant'] ?? null,
                        'weez_ticket_code' => $participant['barcode_id'] ?? null,
                        'weez_event_id' => $participant['id_evenement'] ?? null,
                    ]);
                }
            } catch (\Exception $e) {
                $errors[] = "Ligne $lineNum ($firstname $lastname) : Weezevent - " . $e->getMessage();
            }
        }

        // Email
        if ($doEmail && $email && $checkin->weez_ticket_code) {
            try {
                Mail::to($email)->send(new AccessConfirmed($checkin));
            } catch (\Exception $e) {
                $errors[] = "Ligne $lineNum : email - " . $e->getMessage();
            }
        }

        $imported++;
    }

    $message = "$imported contact(s) importe(s) avec acces.";
    if ($skipped > 0) $message .= " $skipped ignore(s) (deja existants).";

    return redirect()->route('admin.contacts.index')
        ->with('success', $message)
        ->with($errors ? 'error' : 'info', implode(' | ', $errors));
}

}
