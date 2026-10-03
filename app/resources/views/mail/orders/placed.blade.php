<x-mail::message>
# Dziękujemy za zamówienie!

Twoje zamówienie **{{ $order->number }}** zostało przyjęte. Poniżej znajdziesz jego podsumowanie.

@include('mail.orders.partials.summary')

@if(config('shop.bank_account'))
## Płatność

Prosimy o przelew na kwotę **{{ number_format($order->total, 2, ',', ' ') }} zł**:

- odbiorca: {{ config('shop.bank_recipient') }}
- numer konta: {{ config('shop.bank_account') }}
- tytuł przelewu: {{ $order->number }}

Zamówienie przekażemy do realizacji po zaksięgowaniu wpłaty.
@endif

W razie pytań po prostu odpowiedz na tę wiadomość.

Pozdrawiamy,<br>
{{ config('app.name') }}
</x-mail::message>
