<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Potwierdzenie zamówienia wysyłane do klienta.
 */
class OrderPlaced extends Mailable
{
    public function __construct(
        public Order $order
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Potwierdzenie zamówienia ' . $this->order->number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.placed',
        );
    }

    /**
     * Regulamin w brzmieniu z dnia zamówienia - klient musi go dostać
     * na trwałym nośniku razem z potwierdzeniem umowy.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => view('store.terms-document')->render(),
                'regulamin-migdas.html'
            )->withMime('text/html'),
        ];
    }
}
