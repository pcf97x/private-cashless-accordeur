<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
  protected $fillable = [
        'room_id',
        'time_slot_id',
        'pricing_profile_id',
        'date',
        'start_at',
        'end_at',
        'name',
        'email',
        'phone',
        'price',
        'status',
    ];
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'date'     => 'date',
    ];
}
