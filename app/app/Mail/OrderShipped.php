<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Informacja dla klienta o nadaniu przesyłki.
 */
class OrderShipped extends Mailable
{
    public function __construct(
        public Order $order
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Zamówienie ' . $this->order->number . ' zostało wysłane',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.shipped',
        );
    }
}
