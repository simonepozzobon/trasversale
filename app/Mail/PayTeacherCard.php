<?php

namespace App\Mail;

use App\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayTeacherCard extends Mailable
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
         $this->sender = env('ADMIN_EMAIL', 'demo@example.com');
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
            ->subject('Pagamento Inviato')
            ->with([
                'code' => $this->data['code'],
                'product' => $this->product,
            ])
            ->markdown('mails.pay-teacher-card');
    }
}
