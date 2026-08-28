<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationOption extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2',
    ];
}
