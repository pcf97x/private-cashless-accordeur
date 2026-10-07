<?php

namespace App\Console\Commands;

use App\Mail\WeeklyReport;
use App\Models\Checkin;
use App\Models\Reservation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyReport extends Command
{
    protected $signature = 'report:weekly {--to= : Email address to send to} {--date= : Start date of the week (defaults to last Monday)}';
    protected $description = 'Generate and send the weekly activity report';

    public function handle()
    {
        // Determine week range
        if ($this->option('date')) {
            $start = Carbon::parse($this->option('date'))->startOfDay();
        } else {
            $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->subWeek();
        }
        $end = $start->copy()->addDays(6)->endOfDay();

        $dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $periodLabel = 'Semaine du ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y');

        $this->info("Generating report: $periodLabel");

        // Reservations
        $reservations = Reservation::with('room')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->whereIn('status', ['paid', 'pending', 'devis'])
            ->orderBy('date')
            ->orderBy('start_at')
            ->get();

        $reservationsByDay = [];
        $totalCa = 0;
        foreach ($reservations as $r) {
            $dayKey = $dayNames[$r->date->dayOfWeek] . ' ' . $r->date->format('d/m/Y');
            $reservationsByDay[$dayKey][] = [
                'time' => $r->start_at->format('H:i') . '-' . $r->end_at->format('H:i'),
                'room' => $r->room->name ?? '?',
                'client' => $r->name,
                'event' => $r->event_name,
                'price' => number_format($r->price - $r->discount_amount, 2, ',', ' ') . ' EUR',
                'status' => $r->status === 'paid' ? 'Payee' : ($r->status === 'pending' ? 'En attente' : 'Devis'),
            ];
            if ($r->status === 'paid') {
                $totalCa += $r->price - $r->discount_amount;
            }
        }

        // Checkins
        $checkins = Checkin::whereBetween('scan_date', [$start->toDateString(), $end->toDateString()])
            ->whereNotNull('entry_at')
            ->orderBy('scan_date')
            ->orderBy('entry_at')
            ->get();

        $checkinsByDay = [];
        $uniqueEmails = [];
        foreach ($checkins as $c) {
            $dayKey = $dayNames[$c->scan_date->dayOfWeek] . ' ' . $c->scan_date->format('d/m/Y');

            $duration = '—';
            if ($c->entry_at && $c->exit_at) {
                $diff = $c->entry_at->diff($c->exit_at);
                $duration = $diff->h . 'h' . str_pad($diff->i, 2, '0', STR_PAD_LEFT);
            }

            $checkinsByDay[$dayKey][] = [
                'name' => trim($c->firstname . ' ' . $c->lastname),
                'company' => $c->company,
                'entry' => $c->entry_at ? $c->entry_at->format('H:i') : '—',
                'exit' => $c->exit_at ? $c->exit_at->format('H:i') : '—',
                'duration' => $duration,
                'purpose' => $c->purpose,
            ];

            if ($c->email) $uniqueEmails[$c->email] = true;
        }

        $data = [
            'period_label' => $periodLabel,
            'week_start' => $start->toDateString(),
            'stats' => [
                'total_reservations' => $reservations->count(),
                'total_ca' => $totalCa,
                'total_checkins' => $checkins->count(),
                'unique_visitors' => count($uniqueEmails),
            ],
            'reservations_by_day' => $reservationsByDay,
            'checkins_by_day' => $checkinsByDay,
        ];

        // Send
        $to = $this->option('to')
            ?: Setting::get('conciergerie_email', 'laconciergerie@groupe-aprosep.com');

        $recipients = array_map('trim', explode(',', $to));

        Mail::to($recipients)->send(new WeeklyReport($data));

        $this->info("Report sent to: $to");

        return 0;
    }
}
