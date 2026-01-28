<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ITCareMailFollowup extends Mailable
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
        return $this->markdown('ITCareMailFollowup')
                    ->subject('Update Your Ticket IT Care')
                    ->with('dataemail', $this->dataemail);
    }
}
