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
        'event_name',
        'event_visibility',
        'price',
        'status',
        'payment_method',
        'stripe_session_id',
        'devis_token',
        'devis_notes',
        'discount_amount',
        'discount_label',
    ];
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'date'     => 'date',
    ];
    
    
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

        public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function pricingProfile()
    {
        return $this->belongsTo(PricingProfile::class);
    }

    public function options()
    {
        return $this->belongsToMany(ReservationOption::class)
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    public function supplements()
    {
        return $this->hasMany(ReservationSupplement::class);
    }

    /**
     * Check if a slot conflicts with existing reservations (including overlapping time slots).
     * E.g. AM (7-13) conflicts with FULL_DAY (7-18) and vice versa.
     */
    public static function hasConflict(int $roomId, int $timeSlotId, string $date, ?int $excludeId = null): bool
    {
        $slot = TimeSlot::find($timeSlotId);
        if (!$slot) return false;

        // Find all time slots that overlap with the requested one
        $overlappingSlotIds = TimeSlot::where('start_time', '<', $slot->end_time)
            ->where('end_time', '>', $slot->start_time)
            ->pluck('id');

        $query = static::where('room_id', $roomId)
            ->whereIn('time_slot_id', $overlappingSlotIds)
            ->whereDate('date', $date)
            ->whereIn('status', ['pending', 'paid', 'devis']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}

