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
          href="{{ asset('css/store.css') }}">
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

            <span class="cart-placeholder">
                Koszyk
            </span>
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
    </div>
</footer>

@stack('scripts')

</body>
</html>
