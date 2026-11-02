<x-mail::message>
# Twoje zamówienie jest w drodze

Zamówienie **{{ $order->number }}** zostało nadane.

- przewoźnik: {{ $order->carrier }}
- numer przesyłki: {{ $order->tracking_number }}

Adres dostawy:<br>
{{ $order->first_name }} {{ $order->last_name }}<br>
{{ $order->street }} {{ $order->building_number }}{{ $order->apartment_number ? '/' . $order->apartment_number : '' }}<br>
{{ $order->postal_code }} {{ $order->city }}

W razie pytań po prostu odpowiedz na tę wiadomość.

Pozdrawiamy,<br>
{{ config('app.name') }}
</x-mail::message>
