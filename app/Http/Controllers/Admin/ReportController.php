<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'day');
        $date = $request->get('date', now()->toDateString());

        [$startDate, $endDate, $periodLabel] = $this->resolvePeriod($period, $date);

        $checkins = $this->getCheckins($startDate, $endDate);
        $stats = $this->computeStats($checkins);

        return view('admin.reports.index', compact(
            'checkins', 'stats', 'period', 'date', 'startDate', 'endDate', 'periodLabel'
        ));
    }

    public function exportCsv(Request $request)
    {
        $period = $request->get('period', 'day');
        $date = $request->get('date', now()->toDateString());

        [$startDate, $endDate, $periodLabel] = $this->resolvePeriod($period, $date);
        $checkins = $this->getCheckins($startDate, $endDate);

        $filename = 'presences-' . $startDate . '-' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($checkins) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Date', 'Nom', 'Prenom', 'Email', 'Societe', 'Motif', 'Entree', 'Sortie', 'Duree'], ';');

            foreach ($checkins as $c) {
                $duration = '';
                if ($c->entry_at && $c->exit_at) {
                    $diff = Carbon::parse($c->entry_at)->diff(Carbon::parse($c->exit_at));
                    $duration = $diff->format('%Hh%I');
                }

                fputcsv($file, [
                    $c->scan_date ? Carbon::parse($c->scan_date)->format('d/m/Y') : '',
                    $c->lastname ?? '',
                    $c->firstname ?? '',
                    $c->email ?? '',
                    $c->company ?? '',
                    $c->purpose ?? '',
                    $c->entry_at ? Carbon::parse($c->entry_at)->format('H:i') : '',
                    $c->exit_at ? Carbon::parse($c->exit_at)->format('H:i') : '',
                    $duration,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $period = $request->get('period', 'day');
        $date = $request->get('date', now()->toDateString());

        [$startDate, $endDate, $periodLabel] = $this->resolvePeriod($period, $date);
        $checkins = $this->getCheckins($startDate, $endDate);
        $stats = $this->computeStats($checkins);

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'checkins', 'stats', 'startDate', 'endDate', 'periodLabel'
        ))->setPaper('a4', 'landscape');

        $filename = 'presences-' . $startDate . '-' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    private function resolvePeriod(string $period, string $date): array
    {
        $ref = Carbon::parse($date);

        return match ($period) {
            'day' => [
                $ref->toDateString(),
                $ref->toDateString(),
                $ref->translatedFormat('l d F Y'),
            ],
            'month' => [
                $ref->copy()->startOfMonth()->toDateString(),
                $ref->copy()->endOfMonth()->toDateString(),
                $ref->translatedFormat('F Y'),
            ],
            'semester' => [
                $ref->month <= 6
                    ? $ref->copy()->startOfYear()->toDateString()
                    : $ref->copy()->setMonth(7)->startOfMonth()->toDateString(),
                $ref->month <= 6
                    ? $ref->copy()->setMonth(6)->endOfMonth()->toDateString()
                    : $ref->copy()->endOfYear()->toDateString(),
                $ref->month <= 6
                    ? '1er semestre ' . $ref->year
                    : '2e semestre ' . $ref->year,
            ],
            default => [$ref->toDateString(), $ref->toDateString(), $ref->toDateString()],
        };
    }

    private function getCheckins(string $startDate, string $endDate)
    {
        return Checkin::whereNotNull('scan_date')
            ->whereDate('scan_date', '>=', $startDate)
            ->whereDate('scan_date', '<=', $endDate)
            ->orderBy('scan_date', 'desc')
            ->orderBy('entry_at', 'desc')
            ->get();
    }

    private function computeStats($checkins): array
    {
        $totalEntries = $checkins->count();
        $uniquePersons = $checkins->pluck('email')->filter()->unique()->count();
        $withExit = $checkins->whereNotNull('exit_at');

        $totalMinutes = 0;
        foreach ($withExit as $c) {
            $totalMinutes += Carbon::parse($c->entry_at)->diffInMinutes(Carbon::parse($c->exit_at));
        }
        $avgMinutes = $withExit->count() > 0 ? round($totalMinutes / $withExit->count()) : 0;

        $uniqueDays = $checkins->pluck('scan_date')->map(fn($d) => Carbon::parse($d)->toDateString())->unique()->count();

        return [
            'total_entries' => $totalEntries,
            'unique_persons' => $uniquePersons,
            'unique_days' => $uniqueDays,
            'avg_duration' => sprintf('%dh%02d', intdiv($avgMinutes, 60), $avgMinutes % 60),
            'total_hours' => sprintf('%dh%02d', intdiv($totalMinutes, 60), $totalMinutes % 60),
        ];
    }
}
