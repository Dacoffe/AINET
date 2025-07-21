<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfContent;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param string $pdfContent Conteúdo binário do PDF
     */
    public function __construct(Order $order, $pdfContent)
    {
        $this->order = $order;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
    return $this->subject('Your Order #'.$this->order->id.' Receipt (Pending)')
               ->markdown('cart.emails.order_receipt', ['status' => 'pending'])
               ->attachData($this->pdfContent, 'receipt_'.$this->order->id.'.pdf', [
                   'mime' => 'application/pdf',
               ]);
}
}
