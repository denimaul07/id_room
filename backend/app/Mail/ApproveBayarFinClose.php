<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproveBayarFinClose extends Mailable
{
    use Queueable, SerializesModels;
    public $dataemail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataemail)
    {
        $this->dataemail = $dataemail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('approveBayarFinClose')
                    ->subject('Update Approval Instruksi Pembayaran')
                    ->with('dataemail', $this->dataemail);
    }
}
