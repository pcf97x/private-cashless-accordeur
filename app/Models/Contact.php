<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'company',
        'email',
        'phone',
    ];

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }
    
}
