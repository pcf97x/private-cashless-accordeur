use App\Models\Contact;
use App\Models\Checkin;
use Illuminate\Support\Str;

public function store(Request $request, WeezeventParticipantService $weezevent)
{
    $request->validate([
        'firstname' => 'required|string',
        'lastname'  => 'required|string',
        'email'     => 'nullable|email',
        'company'   => 'nullable|string',
        'purpose'   => 'nullable|string',
    ]);

    /** 1️⃣ CONTACT (créé ou récupéré) */
    $contact = Contact::firstOrCreate(
        [
            'email' => $request->email,
        ],
        [
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'company'   => $request->company,
        ]
    );

    /** 2️⃣ CHECKIN lié AU CONTACT */
    $checkin = Checkin::create([
        'contact_id' => $contact->id,
        'firstname'  => $request->firstname,
        'lastname'   => $request->lastname,
        'company'    => $request->company,
        'email'      => $request->email,
        'purpose'    => $request->purpose,
        'qr_token'   => (string) Str::uuid(),
    ]);

    /** 3️⃣ Création Weezevent */
    $response = $weezevent->createParticipant([
        'firstname' => $checkin->firstname,
        'lastname'  => $checkin->lastname,
        'email'     => $checkin->email,
    ]);

    $participant = $response['participants'][0] ?? null;

    if ($participant) {
        $checkin->update([
            'weez_participant_id' => $participant['id_participant'] ?? null,
            'weez_ticket_code'    => $participant['barcode_id'] ?? null,
            'weez_event_id'       => $participant['id_evenement'] ?? null,
        ]);
    }

    return view('access.success', [
        'barcode' => $checkin->weez_ticket_code,
    ]);
}
