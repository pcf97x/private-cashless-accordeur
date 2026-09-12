<?php

namespace App\Mail;

use App\Models\ReservationSupplement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupplementPaymentRequest extends Mailable
{
    use Queueable, SerializesModels;

    public ReservationSupplement $supplement;
    public string $payUrl;

    public function __construct(ReservationSupplement $supplement)
    {
        $this->supplement = $supplement;
        $this->payUrl = url('/supplement/' . $supplement->token . '/pay');
    }

    public function build()
    {
        return $this
            ->subject('Paiement complementaire - L\'Accordeur')
            ->view('emails.supplement-payment');
    }
}
