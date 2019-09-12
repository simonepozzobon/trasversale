<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CartInfoAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data = $data;
        $this->subject = 'Richiesta di informazioni da ' . $data['name'] . ' ' . $data['surname'];
        $this->sender = $data['sender'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender)
            ->subject($this->subject)
            ->with([
                'message' => $this->data['message'],
                'item' => $this->data['item']
            ])
            ->markdown('mails.cartinfoAdmin');
    }
}
