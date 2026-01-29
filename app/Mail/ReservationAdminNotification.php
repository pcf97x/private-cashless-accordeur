<?php

namespace App\Mail;

use App\Models\Reservation; // ✅ BON namespace
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Reservation $reservation) {}

public function build()
{
    return $this->subject('📅 Nouvelle réservation confirmée')
        ->view('emails.reservation-admin');
}


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Admin Notification',
        );
    }


}
