<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Powiadomienie dla sklepu o nowym zamówieniu.
 */
class NewOrderNotification extends Mailable
{
    public function __construct(
        public Order $order
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nowe zamówienie ' . $this->order->number,
            // "Odpowiedz" trafia od razu do klienta.
            replyTo: [
                new Address(
                    $this->order->email,
                    $this->order->first_name . ' ' . $this->order->last_name
                ),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.notification',
        );
    }
}
