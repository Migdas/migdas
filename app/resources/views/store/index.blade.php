@extends('layouts.store')

@section('title', 'Migdas - Druk 3D')

@section('content')

<section class="hero">

    <div class="container">

        <span class="eyebrow">
            DRUK 3D
        </span>

        <h1>
            Pomysły zamienione<br>
            w prawdziwe przedmioty.
        </h1>

        <p>
            Funkcjonalne i dekoracyjne produkty
            wykonywane metodą druku 3D.
        </p>

        <a href="#produkty"
           class="button">
            Zobacz produkty
        </a>

    </div>

</section>


<section id="produkty"
         class="products-section">

    <div class="container">

        <div class="section-heading">

            <div>
                <span class="eyebrow">
                    SKLEP
                </span>

                <h2>
                    Nasze produkty
                </h2>
            </div>

            <form method="GET"
                  class="search-form">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Szukaj produktu...">

                @if(request('category'))
                    <input
                        type="hidden"
                        name="category"
                        value="{{ request('category') }}">
                @endif

                <button type="submit">
                    Szukaj
                </button>

            </form>

        </div>


        <div class="category-list">

            <a
                href="{{ route('store.products') }}"
                class="category-pill {{ !request('category') ? 'active' : '' }}">

                Wszystkie

            </a>

            @foreach($categories as $category)

                <a
                    href="{{ route('store.products', ['category' => $category->slug]) }}"
                    class="category-pill {{ request('category') === $category->slug ? 'active' : '' }}">

                    {{ $category->name }}

                </a>

            @endforeach

        </div>


        @if($products->count())

            <div class="product-grid">

                @foreach($products as $product)

                    <article class="product-card">

                        <a href="{{ route('store.product', $product) }}"
                           class="product-image">

                            @if($product->main_image)

                                <img
                                    src="{{ asset('storage/' . $product->main_image) }}"
                                    alt="{{ $product->name }}">

                            @else

                                <div class="image-placeholder">
                                    MIGDAS
                                </div>

                            @endif

                        </a>


                        <div class="product-content">

                            @if($product->category)

                                <span class="product-category">
                                    {{ $product->category->name }}
                                </span>

                            @endif


                            <h3>
                                <a href="{{ route('store.product', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>


                            @if($product->short_description)

                                <p class="product-description">
                                    {{ $product->short_description }}
                                </p>

                            @endif


                            <div class="product-bottom">

                                <div class="price">

                                    @if($product->sale_price)

                                        <span class="old-price">
                                            {{ number_format($product->price, 2, ',', ' ') }} zł
                                        </span>

                                        <strong>
                                            {{ number_format($product->sale_price, 2, ',', ' ') }} zł
                                        </strong>

                                    @else

                                        <strong>
                                            {{ number_format($product->price, 2, ',', ' ') }} zł
                                        </strong>

                                    @endif

                                </div>


                                <a
                                    href="{{ route('store.product', $product) }}"
                                    class="product-link">

                                    Zobacz

                                </a>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>


            @if($products->hasPages())

                <div class="pagination-simple">

                    @if($products->previousPageUrl())

                        <a href="{{ $products->previousPageUrl() }}">
                            ← Poprzednia
                        </a>

                    @endif

                    <span>
                        Strona {{ $products->currentPage() }}
                        z {{ $products->lastPage() }}
                    </span>

                    @if($products->nextPageUrl())

                        <a href="{{ $products->nextPageUrl() }}">
                            Następna →
                        </a>

                    @endif

                </div>

            @endif

        @else

            <div class="empty-state">

                <h3>
                    Brak produktów
                </h3>

                <p>
                    Nie znaleziono produktów spełniających kryteria.
                </p>

            </div>

        @endif

    </div>

</section>

@endsection
