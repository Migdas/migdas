<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Migdas - Druk 3D')
    </title>

    <meta name="description"
          content="@yield('description', 'Migdas - produkty tworzone w technologii druku 3D.')">

    <link rel="stylesheet"
          href="{{ asset('css/store.css') }}?v={{ filemtime(public_path('css/store.css')) }}">
</head>

<body>

<header class="site-header">
    <div class="container header-inner">

        <a href="{{ route('store.index') }}"
           class="logo">
            MIGDAS
        </a>

        <nav class="main-nav">
            <a href="{{ route('store.index') }}">
                Strona główna
            </a>

            <a href="{{ route('store.products') }}">
                Sklep
            </a>

            <a
               href="{{ route('cart.index') }}"
               class="cart-placeholder">
 
              Koszyk

           @php
          $cartCount = collect(
                 session('cart', [])
                )->sum('quantity');
              @endphp

                 @if($cartCount > 0)
                   ({{ $cartCount }})
                 @endif

             </a>
            
        </nav>

    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <strong>Migdas</strong>

        <p>
            Produkty tworzone w technologii druku 3D.
        </p>

        <p class="footer-contact">
            Sprzedawca: {{ config('shop.seller.name') }}<br>
            {{ config('shop.seller.address') }}<br>
            E-mail:
            <a href="mailto:{{ config('shop.seller.email') }}">{{ config('shop.seller.email') }}</a><br>
            Telefon:
            <a href="tel:+48{{ str_replace(' ', '', config('shop.seller.phone')) }}">{{ config('shop.seller.phone') }}</a>
        </p>

        <nav class="footer-nav">
            <a href="{{ route('store.terms') }}">
                Regulamin sklepu
            </a>

            <a href="{{ route('store.privacy') }}">
                Polityka prywatności
            </a>
        </nav>
    </div>
</footer>

@stack('scripts')

</body>
</html>
