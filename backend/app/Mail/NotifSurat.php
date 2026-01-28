<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifSurat extends Mailable
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
        return $this->markdown('NotifSurat')
                    ->subject('CIS Notifikasi Surat Keluar')
                    ->with('dataemail', $this->dataemail);
    }
}
