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
    'weez_ticket_code',
    'weez_event_id',
    'weez_participant_id',
    'scan_date',
    'entry_at',
    'exit_at',
];
}