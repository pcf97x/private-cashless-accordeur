<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationSupplement extends Model
{
    protected $fillable = [
        'reservation_id',
        'label',
        'description',
        'amount',
        'status',
        'payment_method',
        'token',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
