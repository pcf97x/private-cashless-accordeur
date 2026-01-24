<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'code',
        'label',
        'start_time',
        'end_time',
        'order_index',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
