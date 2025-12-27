<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'purpose',
        'qr_token',
        'checked_in_at',
    ];
}