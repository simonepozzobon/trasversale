<?php

namespace App\Mail;

use App\Product;

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
    public function __construct($data, Product $product)
    {
        $this->data = $data;
        $this->subject = 'Richiesta informazioni da ' . $data['name'];
        $this->sender = $data['sender'];
        $this->product = $product;
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
                'product' => $this->product,
                'name' => $this->data['name'],
                'surname' => $this->data['surname']
            ])
            ->markdown('mails.cartinfoAdmin');
    }
}
