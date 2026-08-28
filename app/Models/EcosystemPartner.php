<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcosystemPartner extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'description',
        'contact_name',
        'contact_email',
        'contact_phone',
        'website',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
