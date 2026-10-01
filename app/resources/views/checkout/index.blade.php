@extends('layouts.store')

@section('title', 'Zamówienie - Migdas')

@section('content')

<section class="checkout-page">

<div class="container">

    <h1>Finalizacja zamówienia</h1>

    <form
        method="POST"
        action="{{ route('checkout.store') }}"
        class="checkout-layout">

        @csrf

        <div class="checkout-form">

            @if($errors->any())

                <div class="checkout-errors">

                    <strong>
                        Sprawdź formularz:
                    </strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif


            <section class="checkout-box">

                <h2>Dane kontaktowe</h2>

                <div class="form-grid">

                    <label>
                        Imię
                        <input
                            name="first_name"
                            value="{{ old('first_name') }}"
                            required>
                    </label>

                    <label>
                        Nazwisko
                        <input
                            name="last_name"
                            value="{{ old('last_name') }}"
                            required>
                    </label>

                    <label>
                        E-mail
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required>
                    </label>

                    <label>
                        Telefon
                        <input
                            name="phone"
                            value="{{ old('phone') }}"
                            required>
                    </label>

                </div>

            </section>


            <section class="checkout-box">

                <h2>Adres dostawy</h2>

                <div class="form-grid">

                    <label class="wide">
                        Ulica

                        <input
                            name="street"
                            value="{{ old('street') }}"
                            required>
                    </label>

                    <label>
                        Numer budynku

                        <input
                            name="building_number"
                            value="{{ old('building_number') }}"
                            required>
                    </label>

                    <label>
                        Numer mieszkania

                        <input
                            name="apartment_number"
                            value="{{ old('apartment_number') }}">
                    </label>

                    <label>
                        Kod pocztowy

                        <input
                            name="postal_code"
                            placeholder="00-000"
                            value="{{ old('postal_code') }}"
                            required>
                    </label>

                    <label>
                        Miasto

                        <input
                            name="city"
                            value="{{ old('city') }}"
                            required>
                    </label>

                </div>

            </section>


            <section class="checkout-box">

                <h2>Dostawa</h2>

                <label class="shipping-option">

                    <input
                        type="radio"
                        name="shipping_method"
                        value="courier"
                        checked>

                    <span>
                        <strong>Kurier</strong>
                        <small>16,99 zł</small>
                    </span>

                </label>

                <label class="shipping-option">

                    <input
                        type="radio"
                        name="shipping_method"
                        value="pickup">

                    <span>
                        <strong>Odbiór osobisty</strong>
                        <small>0,00 zł</small>
                    </span>

                </label>

            </section>


            <section class="checkout-box">

                <h2>Uwagi</h2>

                <textarea
                    name="customer_note"
                    rows="4"
                    placeholder="Opcjonalne uwagi do zamówienia">{{ old('customer_note') }}</textarea>

            </section>


            <label class="terms-row">

                <input
                    type="checkbox"
                    name="terms"
                    value="1"
                    required>

                <span>
                    Akceptuję regulamin sklepu i zasady realizacji
                    zamówienia.
                </span>

            </label>

        </div>


        <aside class="checkout-summary">

            <h2>Twoje zamówienie</h2>

            @foreach($items as $item)

                <div class="checkout-product">

                    <div>

                        <strong>
                            {{ $item['variant']->product->name }}
                        </strong>

                        <small>
                            {{ $item['variant']->material->name }}
                            /
                            {{ $item['variant']->color->name }}

                            × {{ $item['quantity'] }}
                        </small>

                    </div>

                    <strong>
                        {{ number_format(
                            $item['total'],
                            2,
                            ',',
                            ' '
                        ) }} zł
                    </strong>

                </div>

            @endforeach


            <div class="summary-row">

                <span>Produkty</span>

                <strong>
                    {{ number_format(
                        $productsTotal,
                        2,
                        ',',
                        ' '
                    ) }} zł
                </strong>

            </div>

            <p class="shipping-info">
                Koszt dostawy zostanie doliczony
                zgodnie z wybraną metodą.
            </p>

            <button
                type="submit"
                class="button checkout-submit">

                Zamawiam

            </button>

        </aside>

    </form>

</div>

</section>

@endsection
