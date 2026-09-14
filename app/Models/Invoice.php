<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'client_name', 'client_email', 'client_phone', 'client_address',
        'programme', 'chorus_reference', 'status',
        'devis_sent_at', 'deposit_amount', 'deposit_received_at',
        'invoice_sent_at', 'paid_at', 'payment_method',
        'notes', 'total_ht',
    ];

    protected $casts = [
        'devis_sent_at' => 'datetime',
        'deposit_received_at' => 'datetime',
        'invoice_sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'deposit_amount' => 'decimal:2',
        'total_ht' => 'decimal:2',
    ];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class)->withTimestamps();
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class)->orderBy('sort_order');
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $shortYear = date('y');
        $key = 'invoice_counter_' . $year;

        $current = (int) Setting::get($key, 0);
        $next = $current + 1;
        Setting::set($key, (string) $next);

        return $shortYear . '-' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    public function recalculateTotal(): void
    {
        $this->update(['total_ht' => $this->lines()->sum('total')]);
    }

    public function getResteDueAttribute(): float
    {
        return max(0, (float) $this->total_ht - (float) $this->deposit_amount);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Brouillon',
            'devis_sent' => 'Devis envoye',
            'sent' => 'Envoyee',
            'paid' => 'Payee',
            'cancelled' => 'Annulee',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'devis_sent' => 'blue',
            'sent' => 'amber',
            'paid' => 'emerald',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
