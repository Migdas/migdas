@extends('layouts.store')

@section('title', 'Koszyk - Migdas')

@section('content')

<section class="cart-page">

<div class="container">

    <h1>Koszyk</h1>

    @if(session('success'))

        <div class="success-message">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="checkout-errors">
            {{ session('error') }}
        </div>

    @endif

    @if($items->isEmpty())

        <div class="empty-state">

            <h2>Twój koszyk jest pusty</h2>

            <p>
                Dodaj produkty, które chcesz zamówić.
            </p>

            <a
                href="{{ route('store.products') }}"
                class="button">

                Przejdź do sklepu

            </a>

        </div>

    @else

        <div class="cart-layout">

            <div class="cart-items">

                @foreach($items as $item)

                    @php
                        $variant = $item['variant'];
                    @endphp

                    <article class="cart-item">

                        <div class="cart-item-image">

                            @if($variant->product->main_image)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        $variant->product->main_image
                                    ) }}"
                                    alt="{{ $variant->product->name }}">

                            @endif

                        </div>


                        <div class="cart-item-info">

                            <h2>
                                {{ $variant->product->name }}
                            </h2>

                            <p>
                                {{ $variant->material->name }}
                                /
                                {{ $variant->color->name }}
                            </p>

                            <small>
                                SKU: {{ $variant->sku }}
                            </small>

                        </div>


                        <div class="cart-item-price">

                            {{ number_format(
                                $item['unit_price'],
                                2,
                                ',',
                                ' '
                            ) }} zł

                        </div>


                        <form
                            method="POST"
                            action="{{ route(
                                'cart.update',
                                $variant
                            ) }}"
                            class="cart-quantity">

                            @csrf
                            @method('PATCH')

                            <input
                                type="number"
                                name="quantity"
                                value="{{ $item['quantity'] }}"
                                min="1"
                                max="99">

                            <button type="submit">
                                Zmień
                            </button>

                        </form>


                        <div class="cart-item-total">

                            <strong>
                                {{ number_format(
                                    $item['total'],
                                    2,
                                    ',',
                                    ' '
                                ) }} zł
                            </strong>

                        </div>


                        <form
                            method="POST"
                            action="{{ route(
                                'cart.remove',
                                $variant
                            ) }}">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="remove-button">

                                Usuń

                            </button>

                        </form>

                    </article>

                @endforeach

            </div>


            <aside class="cart-summary">

                <h2>Podsumowanie</h2>

                <div class="summary-row">

                    <span>Produkty</span>

                    <strong>
                        {{ number_format(
                            $total,
                            2,
                            ',',
                            ' '
                        ) }} zł
                    </strong>

                </div>

                <div class="summary-total">

                    <span>Razem</span>

                    <strong>
                        {{ number_format(
                            $total,
                            2,
                            ',',
                            ' '
                        ) }} zł
                    </strong>

                </div>

                <a
                    href="{{ route('checkout.index') }}"
                    class="button checkout-button">

                    Przejdź do zamówienia

                </a>

                <small>
                    Dostawę wybierzesz w kolejnym kroku.
                </small>

            </aside>

        </div>

    @endif

</div>

</section>

@endsection
