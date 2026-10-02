@extends('layouts.store')

@section('title', $product->name . ' - Migdas')

@section(
    'description',
    $product->short_description ?? $product->name
)

@section('content')

<section class="product-page">

<div class="container">

    <a href="{{ route('store.products') }}"
       class="back-link">

        ← Wróć do sklepu

    </a>


    <div class="product-detail">

        <div class="product-gallery">

            @if($product->main_image)

                <img
                    class="main-product-image"
                    src="{{ asset('storage/' . $product->main_image) }}"
                    alt="{{ $product->name }}">

            @else

                <div class="main-image-placeholder">
                    MIGDAS
                </div>

            @endif

        </div>


        <div class="product-info">

            @if($product->category)

                <span class="eyebrow">
                    {{ mb_strtoupper($product->category->name) }}
                </span>

            @endif


            <h1>
                {{ $product->name }}
            </h1>


            @if($product->short_description)

                <p class="product-lead">
                    {{ $product->short_description }}
                </p>

            @endif


            <div class="detail-price">

                <span id="selected-price">

                    @if($product->sale_price)

                        {{ number_format($product->sale_price, 2, ',', ' ') }} zł

                    @else

                        {{ number_format($product->price, 2, ',', ' ') }} zł

                    @endif

                </span>

            </div>


            @if($product->variants->count())

                <div class="variant-section">

                    <h3>
                        Wybierz wariant
                    </h3>

                    <div class="variant-grid">

                        @foreach($product->variants as $variant)

                            @php

                                $price =
                                    $variant->sale_price
                                    ?? $variant->price
                                    ?? $product->sale_price
                                    ?? $product->price;

                                $leadTime =
                                    $variant->lead_time_days
                                    ?? $product->lead_time_days;

                            @endphp

                            <button
                                type="button"
                                class="variant-card"
                                data-price="{{ $price }}"
                                data-stock="{{ $variant->stock_quantity }}"
                                data-lead="{{ $leadTime }}"
                                data-variant="{{ $variant->id }}">

                                <span class="variant-material">
                                    {{ $variant->material->name }}
                                </span>

                                <span class="variant-color">

                                    @if($variant->color->hex)

                                        <span
                                            class="color-dot"
                                            style="background: {{ $variant->color->hex }}">
                                        </span>

                                    @endif

                                    {{ $variant->color->name }}

                                </span>

                            </button>

                        @endforeach

                    </div>

                </div>


                <div
                    id="availability"
                    class="availability">

                    Wybierz wariant.

                </div>


               <form
                 method="POST"
                 action="{{ route('cart.add') }}"
                 class="add-cart-form">

                @csrf

                 <input
                    type="hidden"
                    name="variant_id"
                    id="selected-variant">

                  <div class="quantity-row">

                  <label for="quantity">
                         Ilość
                  </label>

                <input
                   type="number"
                   id="quantity"
                   name="quantity"
                   value="1"
                   min="1"
                   max="99">

            </div>

            <button
                  type="submit"
                  id="add-to-cart"
                  class="button buy-button"
                  disabled>

                  Dodaj do koszyka

              </button>

           </form>

            @else

                <div class="availability">

                    Czas realizacji:
                    około {{ $product->lead_time_days }} dni

                </div>

            @endif


            @if($product->description)

                <div class="product-long-description">

                    {!! $product->description !!}

                </div>

            @endif

        </div>

    </div>

</div>

</section>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    const variants =
        document.querySelectorAll('.variant-card');

    const price =
        document.getElementById('selected-price');

    const availability =
        document.getElementById('availability');

    const cartButton =
        document.getElementById('add-to-cart');
    
    const variantInput =
        document.getElementById('selected-variant');

    variants.forEach(button => {

        button.addEventListener('click', () => {

            variants.forEach(item =>
                item.classList.remove('selected')
            );

            button.classList.add('selected');


            const variantPrice =
                parseFloat(button.dataset.price);

            const stock =
                parseInt(button.dataset.stock);

            const lead =
                parseInt(button.dataset.lead);


            price.textContent =
                variantPrice.toLocaleString(
                    'pl-PL',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                ) + ' zł';


            if (stock > 0) {

                availability.textContent =
                    `Dostępne od ręki: ${stock} szt.`;

            } else {

                availability.textContent =
                    `Druk na zamówienie — realizacja około ${lead} dni`;

            }


            cartButton.disabled = false;

            variantInput.value =
                button.dataset.variant;

        });

    });

});

</script>

@endpush
