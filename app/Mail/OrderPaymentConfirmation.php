<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Order;

class OrderPaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @param array $orderDetails
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
     public function envelope(): Envelope
     {
         return new Envelope(
             subject: 'Order Payment Confirmation and Shipment Notification',
         );
 
     }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_payment_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
     public function attachments(): array
     {
         return [];
     }

}