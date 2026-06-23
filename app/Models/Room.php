<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
        'capacity',
        'surface_m2',
        'equipments',
        'location',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function rates()
    {
        return $this->hasMany(RoomRate::class);
    }

    public function getPriceRangeAttribute(): ?string
    {
        $prices = $this->rates()->pluck('price')->filter(fn($p) => $p > 0);

        if ($prices->isEmpty()) {
            return null;
        }

        $min = $prices->min();
        $max = $prices->max();

        if ($min === $max) {
            return number_format($min, 0, ',', ' ') . "\u{202F}\u{20AC}";
        }

        return 'Entre ' . number_format($min, 0, ',', ' ') . "\u{20AC}" . ' et ' . number_format($max, 0, ',', ' ') . "\u{20AC}";
    }
}
