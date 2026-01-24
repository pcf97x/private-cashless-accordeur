<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingProfile extends Model
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
