@extends('layouts.store')

@section('title', 'Zamówienie przyjęte - Migdas')

@section('content')

<section class="order-success">

<div class="container">

    <div class="success-card">

        <span class="success-icon">
            ✓
        </span>

        <h1>
            Dziękujemy za zamówienie!
        </h1>

        <p>
            Twoje zamówienie zostało przyjęte.
        </p>

        <div class="order-number">

            Numer zamówienia

            <strong>
                {{ $order->number }}
            </strong>

        </div>

        <p>
            Potwierdzenie wysłaliśmy na adres
            <strong>{{ $order->email }}</strong>.
        </p>


        @if(config('shop.bank_account'))

            <div class="payment-box">

                <h2>Płatność</h2>

                <p>
                    Prosimy o przelew na poniższe dane.
                    Zamówienie przekażemy do realizacji
                    po zaksięgowaniu wpłaty.
                </p>

                <dl>
                    <dt>Kwota</dt>
                    <dd>
                        {{ number_format($order->total, 2, ',', ' ') }} zł
                    </dd>

                    <dt>Odbiorca</dt>
                    <dd>{{ config('shop.bank_recipient') }}</dd>

                    <dt>Numer konta</dt>
                    <dd>{{ config('shop.bank_account') }}</dd>

                    <dt>Tytuł przelewu</dt>
                    <dd>{{ $order->number }}</dd>
                </dl>

            </div>

        @endif


        <div class="order-details">

            <h2>Twoje zamówienie</h2>

            @foreach($order->items as $item)

                <div class="checkout-product">

                    <div>

                        <strong>
                            {{ $item->product_name }}
                        </strong>

                        <small>
                            {{ $item->material_name }}
                            /
                            {{ $item->color_name }}

                            × {{ $item->quantity }}
                        </small>

                    </div>

                    <strong>
                        {{ number_format($item->total, 2, ',', ' ') }} zł
                    </strong>

                </div>

            @endforeach

            <div class="summary-row">

                <span>Produkty</span>

                <strong>
                    {{ number_format($order->products_total, 2, ',', ' ') }} zł
                </strong>

            </div>

            <div class="summary-row">

                <span>
                    Dostawa:
                    {{ $order->shipping_method === 'pickup' ? 'odbiór osobisty' : 'kurier' }}
                </span>

                <strong>
                    {{ number_format($order->shipping_cost, 2, ',', ' ') }} zł
                </strong>

            </div>

            <div class="summary-total">

                <span>Razem</span>

                <strong>
                    {{ number_format($order->total, 2, ',', ' ') }} zł
                </strong>

            </div>

            @if($order->shipping_method === 'courier')

                <p class="order-address">
                    <strong>Adres dostawy</strong><br>
                    {{ $order->first_name }} {{ $order->last_name }}<br>
                    {{ $order->street }}
                    {{ $order->building_number }}{{ $order->apartment_number ? '/' . $order->apartment_number : '' }}<br>
                    {{ $order->postal_code }} {{ $order->city }}
                </p>

            @endif

        </div>

        <a
            href="{{ route('store.index') }}"
            class="button">

            Wróć do sklepu

        </a>

    </div>

</div>

</section>

@endsection
