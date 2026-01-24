<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRate extends Model
{
   protected $fillable = [
    'room_id',
    'time_slot_id',
    'pricing_profile_id',
    'price',
];


    protected $casts = [
        'active' => 'boolean',
    ];
    
}
