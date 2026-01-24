<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'surface_m2',
        'equipments',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
