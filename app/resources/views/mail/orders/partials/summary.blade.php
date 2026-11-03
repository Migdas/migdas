<x-mail::table>
| Produkt | Ilość | Cena |
|:--------|:-----:|-----:|
@foreach($order->items as $item)
| {{ $item->product_name }}<br><small>{{ $item->material_name }} / {{ $item->color_name }}</small> | {{ $item->quantity }} | {{ number_format($item->total, 2, ',', ' ') }} zł |
@endforeach
| Produkty | | {{ number_format($order->products_total, 2, ',', ' ') }} zł |
| Dostawa: {{ $order->shippingLabel() }} | | {{ number_format($order->shipping_cost, 2, ',', ' ') }} zł |
| **Razem** | | **{{ number_format($order->total, 2, ',', ' ') }} zł** |
</x-mail::table>

@if($order->pickup_point)
**Punkt odbioru**<br>
{{ $order->shippingLabel() }}: {{ $order->pickup_point }}<br>
{{ $order->first_name }} {{ $order->last_name }}, tel. {{ $order->phone }}
@elseif($order->isDeliveredToAddress())
**Adres dostawy**<br>
{{ $order->first_name }} {{ $order->last_name }}<br>
{{ $order->street }} {{ $order->building_number }}{{ $order->apartment_number ? '/' . $order->apartment_number : '' }}<br>
{{ $order->postal_code }} {{ $order->city }}
@else
**Odbiór osobisty**<br>
{{ $order->first_name }} {{ $order->last_name }}
@endif

@if($order->customer_note)
**Uwagi do zamówienia**<br>
{{ $order->customer_note }}
@endif
