<?php

namespace App\Mail;

use App\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayTeacherCardAdmin extends Mailable
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
        $this->subject = 'Pagamento con carta docente ricevuto da ' . $data['name'];
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
                'product' => $this->product,
                'code' => $this->data['code'],
                'name' => $this->data['name'],
                'surname' => $this->data['surname']
            ])
            ->markdown('mails.pay-teacher-card-admin');
    }
}
