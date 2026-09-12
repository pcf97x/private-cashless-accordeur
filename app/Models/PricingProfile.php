<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingProfile extends Model
{
    protected $fillable = [
        'code',
        'label',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
