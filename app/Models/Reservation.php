<?php

namespace App\Models;
use App\Models\Room;
use App\Models\TimeSlot;
use App\Models\PricingProfile;
use App\Models\RoomRate;
use App\Models\Reservation;

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
        'stripe_session_id',
    ];
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'date'     => 'date',
    ];
}
