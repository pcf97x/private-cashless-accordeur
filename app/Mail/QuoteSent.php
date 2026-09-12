<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteSent extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;
    public string $acceptUrl;
    public string $declineUrl;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
        $this->acceptUrl = url('/devis/' . $reservation->devis_token . '/accept');
        $this->declineUrl = url('/devis/' . $reservation->devis_token . '/decline');
    }

    public function build()
    {
        return $this
            ->subject('Devis pour votre reservation - L\'Accordeur')
            ->view('emails.quote-sent');
    }
}
