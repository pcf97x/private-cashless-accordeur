<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('reservations')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%$search%")
                  ->orWhere('invoice_number', 'like', "%$search%")
                  ->orWhere('programme', 'like', "%$search%");
            });
        }

        $invoices = $query->get();
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $selectedIds = $request->input('reservation_ids', []);
        $reservations = Reservation::with('room')->whereIn('id', $selectedIds)->get();
        $allReservations = Reservation::with('room')
            ->whereIn('status', ['paid', 'pending'])
            ->latest()
            ->get();

        return view('admin.invoices.create', compact('reservations', 'allReservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_address' => 'nullable|string',
            'programme' => 'nullable|string|max:255',
            'chorus_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'reservation_ids' => 'nullable|array',
            'reservation_ids.*' => 'exists:reservations,id',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'client_address' => $request->client_address,
            'programme' => $request->programme,
            'chorus_reference' => $request->chorus_reference,
            'notes' => $request->notes,
            'status' => 'draft',
        ]);

        // Attach reservations and generate lines
        if ($request->filled('reservation_ids')) {
            $invoice->reservations()->attach($request->reservation_ids);

            $sortOrder = 0;
            foreach ($request->reservation_ids as $resId) {
                $reservation = Reservation::with(['room', 'supplements', 'options'])->find($resId);
                if (!$reservation) continue;

                $price = $reservation->price - $reservation->discount_amount;
                $label = $reservation->room->name . ' — ' . $reservation->date->format('d/m/Y') . ' ' . $reservation->start_at->format('H\hi') . '-' . $reservation->end_at->format('H\hi');

                $invoice->lines()->create([
                    'label' => $label,
                    'quantity' => 1,
                    'unit_price' => $price,
                    'total' => $price,
                    'sort_order' => $sortOrder++,
                ]);

                // Supplements
                foreach ($reservation->supplements->where('status', 'paid') as $sup) {
                    $invoice->lines()->create([
                        'label' => $sup->label,
                        'quantity' => 1,
                        'unit_price' => $sup->amount,
                        'total' => $sup->amount,
                        'sort_order' => $sortOrder++,
                    ]);
                }

                // Options
                foreach ($reservation->options as $opt) {
                    $qty = $opt->pivot->quantity;
                    $unitPrice = $opt->pivot->unit_price;
                    $invoice->lines()->create([
                        'label' => $opt->name,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'total' => $qty * $unitPrice,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        $invoice->recalculateTotal();

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Facture ' . $invoice->invoice_number . ' creee.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['lines', 'reservations.room']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('lines');
        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_address' => 'nullable|string',
            'programme' => 'nullable|string|max:255',
            'chorus_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($request->only([
            'client_name', 'client_email', 'client_phone', 'client_address',
            'programme', 'chorus_reference', 'notes',
        ]));

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Facture mise a jour.');
    }

    public function addLine(Request $request, Invoice $invoice)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $invoice->lines()->create([
            'label' => $request->label,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'sort_order' => $invoice->lines()->max('sort_order') + 1,
        ]);

        $invoice->recalculateTotal();

        return back()->with('success', 'Ligne ajoutee.');
    }

    public function removeLine(InvoiceLine $line)
    {
        $invoice = $line->invoice;
        $line->delete();
        $invoice->recalculateTotal();

        return back()->with('success', 'Ligne supprimee.');
    }

    public function markDevisSent(Invoice $invoice)
    {
        $invoice->update(['status' => 'devis_sent', 'devis_sent_at' => now()]);
        return back()->with('success', 'Devis marque comme envoye.');
    }

    public function recordDeposit(Request $request, Invoice $invoice)
    {
        $request->validate([
            'deposit_amount' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'deposit_amount' => $request->deposit_amount,
            'deposit_received_at' => now(),
        ]);

        return back()->with('success', 'Acompte enregistre.');
    }

    public function markSent(Invoice $invoice)
    {
        $invoice->update(['status' => 'sent', 'invoice_sent_at' => now()]);
        return back()->with('success', 'Facture marquee comme envoyee.');
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method,
        ]);

        return back()->with('success', 'Facture marquee comme payee.');
    }

    public function cancel(Invoice $invoice)
    {
        $invoice->update(['status' => 'cancelled']);
        return back()->with('success', 'Facture annulee.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['lines', 'reservations.room']);

        $settings = [
            'company_name' => Setting::get('invoice_company_name', "L'Accordeur - Pole Associatif de Guyane"),
            'company_address' => Setting::get('invoice_company_address', ''),
            'company_siret' => Setting::get('invoice_company_siret', ''),
            'company_phone' => Setting::get('invoice_company_phone', ''),
            'company_email' => Setting::get('invoice_company_email', ''),
            'payment_info' => Setting::get('invoice_payment_info', ''),
        ];

        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice', 'settings'));

        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }
}
