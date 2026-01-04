<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $table = 'checkins';

    protected $fillable = [
        'firstname',
        'lastname',
        'company',
        'email',
        'purpose',
        'qr_token',

        'weez_ticket_code',
        'weez_event_id',
        'weez_participant_id',

        'scan_date',
        'entry_at',
        'exit_at',
    ];

    protected $casts = [
        'scan_date' => 'date',
        'entry_at'  => 'datetime',
        'exit_at'   => 'datetime',
    ];

public function contact()
{
    return $this->belongsTo(Contact::class);
}


}
