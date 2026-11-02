<x-mail::message>
# Nowe zamówienie {{ $order->number }}

@unless($customerNotified)
**Uwaga: nie udało się wysłać potwierdzenia do klienta.** Według § 3 regulaminu umowa zostaje zawarta dopiero po jego otrzymaniu, więc skontaktuj się z klientem i prześlij potwierdzenie z regulaminem.

@endunless
**Klient:** {{ $order->first_name }} {{ $order->last_name }}<br>
**E-mail:** {{ $order->email }}<br>
**Telefon:** {{ $order->phone }}

@include('mail.orders.partials.summary')

<x-mail::button :url="url('/admin/orders/' . $order->id)">
Otwórz w panelu
</x-mail::button>
</x-mail::message>
