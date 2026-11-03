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


            <section
                id="shipping-address"
                class="checkout-box">

                <h2>Adres dostawy</h2>

                <div class="form-grid">

                    <label class="wide">
                        Ulica

                        <input
                            name="street"
                            value="{{ old('street') }}"
                            data-address-required
                            required>
                    </label>

                    <label>
                        Numer budynku

                        <input
                            name="building_number"
                            value="{{ old('building_number') }}"
                            data-address-required
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
                            data-address-required
                            required>
                    </label>

                    <label>
                        Miasto

                        <input
                            name="city"
                            value="{{ old('city') }}"
                            data-address-required
                            required>
                    </label>

                </div>

            </section>


            <section class="checkout-box">

                <h2>Dostawa</h2>

                <p class="parcel-size-note">
                    Paczka: {{ \App\Support\ParcelSize::SHORT_LABELS[$parcelSize] }}
                    (wg największego produktu w koszyku).
                    @if($parcelSize === \App\Support\ParcelSize::LARGE)
                        Ta paczka nie zmieści się do paczkomatu - dostępna jest tylko wysyłka kurierem.
                    @endif
                </p>

                @php
                    $selectedMethod =
                        $shippingMethods->firstWhere('code', old('shipping_method'))
                        ?? $shippingMethods->first();
                @endphp

                @foreach($shippingMethods as $method)

                    <label class="shipping-option">

                        <input
                            type="radio"
                            name="shipping_method"
                            value="{{ $method->code }}"
                            data-cost="{{ $method->priceFor($parcelSize) }}"
                            data-requires-point="{{ $method->requires_point ? '1' : '0' }}"
                            data-map-url="{{ $method->point_map_url }}"
                            @checked($selectedMethod?->is($method))
                            required>

                        <span>
                            <span class="shipping-option-name">
                                <strong>{{ $method->name }}</strong>
                                @if($method->description)
                                    <small>{{ $method->description }}</small>
                                @endif
                            </span>
                            <strong class="shipping-option-price">
                                {{ number_format($method->priceFor($parcelSize), 2, ',', ' ') }} zł
                            </strong>
                        </span>

                    </label>

                @endforeach


                <div
                    id="pickup-point"
                    class="pickup-point"
                    @if(! $selectedMethod?->requires_point) hidden @endif>

                    <label>
                        Kod paczkomatu / punktu odbioru

                        <input
                            name="pickup_point"
                            placeholder="np. WAW01M"
                            maxlength="100"
                            value="{{ old('pickup_point') }}"
                            @required($selectedMethod?->requires_point)>
                    </label>

                    <small>
                        Nie znasz kodu?
                        <a
                            id="pickup-point-map"
                            href="{{ $selectedMethod?->point_map_url ?: '#' }}"
                            target="_blank"
                            rel="noopener">Znajdź punkt na mapie</a>.
                    </small>


                </div>

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
                    @checked(old('terms'))
                    required>

                <span>
                    Zapoznałem/-am się i akceptuję
                    <a
                        href="{{ route('store.terms') }}"
                        target="_blank"
                        rel="noopener">regulamin sklepu</a>.
                    Wiem, że zamówienie wiąże się z obowiązkiem zapłaty.
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

            @php
                $selectedShippingCost = (float) ($selectedMethod?->priceFor($parcelSize) ?? 0);
            @endphp

            <div class="summary-row">

                <span>Dostawa</span>

                <strong id="shipping-cost">
                    {{ number_format($selectedShippingCost, 2, ',', ' ') }} zł
                </strong>

            </div>

            <div class="summary-total">

                <span>Razem</span>

                <strong
                    id="order-total"
                    data-products-total="{{ $productsTotal }}">
                    {{ number_format($productsTotal + $selectedShippingCost, 2, ',', ' ') }} zł
                </strong>

            </div>

            <button
                type="submit"
                class="button checkout-submit">

                Zamawiam z obowiązkiem zapłaty

            </button>

        </aside>

    </form>

</div>

</section>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    const methods =
        document.querySelectorAll('input[name="shipping_method"]');

    const address =
        document.getElementById('shipping-address');

    const shippingCost =
        document.getElementById('shipping-cost');

    const orderTotal =
        document.getElementById('order-total');

    const pickupPoint =
        document.getElementById('pickup-point');

    const pickupPointInput =
        pickupPoint.querySelector('input[name="pickup_point"]');

    const pickupPointMap =
        document.getElementById('pickup-point-map');

    const format = value =>
        value.toLocaleString(
            'pl-PL',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        ) + ' zł';

    const refresh = () => {

        const selected =
            document.querySelector('input[name="shipping_method"]:checked');

        if (!selected) {
            return;
        }

        const cost =
            parseFloat(selected.dataset.cost);

        const isPoint =
            selected.dataset.requiresPoint === '1';

        shippingCost.textContent =
            format(cost);

        orderTotal.textContent =
            format(parseFloat(orderTotal.dataset.productsTotal) + cost);

        // Przy dostawie do punktu adres nie jest potrzebny - wystarczy kod punktu.
        address.hidden = isPoint;

        address
            .querySelectorAll('[data-address-required]')
            .forEach(input => input.required = !isPoint);

        pickupPoint.hidden = !isPoint;
        pickupPointInput.required = isPoint;

        if (selected.dataset.mapUrl) {
            pickupPointMap.href = selected.dataset.mapUrl;
        }

    };

    methods.forEach(method =>
        method.addEventListener('change', refresh)
    );

    refresh();

});

</script>

@endpush
