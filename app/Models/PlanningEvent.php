<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanningEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'color',
        'active',
        'room_id',
        'time_slot_id',
        'visibility',
    ];

    protected $casts = [
        'date' => 'date',
        'active' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
