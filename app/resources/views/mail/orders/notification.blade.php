<x-mail::message>
# Nowe zamówienie {{ $order->number }}

**Klient:** {{ $order->first_name }} {{ $order->last_name }}<br>
**E-mail:** {{ $order->email }}<br>
**Telefon:** {{ $order->phone }}

@include('mail.orders.partials.summary')

<x-mail::button :url="url('/admin/orders/' . $order->id)">
Otwórz w panelu
</x-mail::button>
</x-mail::message>
