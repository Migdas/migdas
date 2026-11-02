@extends('layouts.store')

@section('title', 'Polityka prywatności - Migdas')

@section('description', 'Polityka prywatności sklepu internetowego Migdas: jakie dane zbieramy, po co i jakie masz prawa.')

@php
    $seller = config('shop.seller');
@endphp

@section('content')

<section class="legal-page">

<div class="container legal-content">

    <h1>Polityka prywatności sklepu Migdas</h1>

    <p class="legal-meta">
        Obowiązuje od: 2 listopada 2026 r.
    </p>


    <h2>1. Administrator danych</h2>

    <p>
        Administratorem Twoich danych osobowych jest {{ $seller['name'] }},
        {{ $seller['address'] }}@if($seller['unregistered']), prowadzący działalność nierejestrowaną@endif
        (dalej: „Administrator”). W sprawach dotyczących danych osobowych możesz
        pisać na adres <a href="mailto:{{ $seller['email'] }}">{{ $seller['email'] }}</a>
        lub dzwonić pod numer {{ $seller['phone'] }} (w godz. 12:00–16:00).
    </p>


    <h2>2. Jakie dane zbieramy</h2>

    <p>
        Przy składaniu zamówienia podajesz: imię i nazwisko, adres e-mail,
        numer telefonu, adres dostawy oraz ewentualne uwagi do zamówienia.
        Zapisujemy też treść zamówienia i informację o płatności.
        Podanie tych danych jest dobrowolne, ale bez nich nie możemy przyjąć
        i zrealizować zamówienia.
    </p>


    <h2>3. Cele i podstawy przetwarzania</h2>

    <ul>
        <li>zawarcie i wykonanie umowy sprzedaży, w tym kontakt w sprawie
            zamówienia i wysyłka – art. 6 ust. 1 lit. b RODO;</li>
        <li>rozpatrywanie reklamacji i oświadczeń o odstąpieniu od umowy
            oraz prowadzenie wymaganej ewidencji sprzedaży i dokumentów
            – art. 6 ust. 1 lit. c RODO;</li>
        <li>ustalenie, dochodzenie lub obrona roszczeń – art. 6 ust. 1 lit. f
            RODO (prawnie uzasadniony interes Administratora);</li>
        <li>odpowiedź na wiadomość wysłaną e-mailem lub telefonicznie
            – art. 6 ust. 1 lit. f RODO.</li>
    </ul>

    <p>
        Nie wysyłamy newslettera, nie profilujemy i nie podejmujemy wobec Ciebie
        decyzji w sposób zautomatyzowany.
    </p>


    <h2>4. Komu przekazujemy dane</h2>

    <ul>
        <li>przewoźnikom doręczającym przesyłki ({{ implode(', ', config('shop.carriers')) }})
            – w zakresie potrzebnym do doręczenia;</li>
        <li>dostawcy serwera, na którym działa Sklep;</li>
        <li>dostawcy usługi wysyłki wiadomości e-mail (Brevo);</li>
        <li>dostawcy obsługi domeny i przekierowania poczty sklepu (Cloudflare);</li>
        <li>dostawcy skrzynki, w której obsługujemy korespondencję i powiadomienia
            o zamówieniach (Google, usługa Gmail);</li>
        <li>bankowi obsługującemu rachunek, na który wpłacasz należność;</li>
        <li>organom publicznym, gdy wymagają tego przepisy.</li>
    </ul>

    <p>
        Cloudflare i Google mogą przetwarzać dane także poza Europejskim Obszarem
        Gospodarczym, w tym w Stanach Zjednoczonych. Podstawą takiego przekazania
        jest decyzja Komisji Europejskiej o odpowiednim stopniu ochrony
        (EU-US Data Privacy Framework) lub standardowe klauzule umowne.
    </p>


    <h2>5. Jak długo przechowujemy dane</h2>

    <p>
        Dane związane z zamówieniem przechowujemy przez okres przedawnienia
        roszczeń z umowy sprzedaży, a dokumenty sprzedaży – przez okres
        wymagany przepisami prawa. Korespondencję niezwiązaną z zamówieniem
        przechowujemy do czasu załatwienia sprawy.
    </p>


    <h2>6. Twoje prawa</h2>

    <p>
        Masz prawo dostępu do swoich danych, ich sprostowania, usunięcia,
        ograniczenia przetwarzania, przeniesienia oraz wniesienia sprzeciwu
        wobec przetwarzania opartego na prawnie uzasadnionym interesie.
        Aby skorzystać z tych praw, napisz na adres {{ $seller['email'] }}.
    </p>

    <p>
        Masz też prawo wnieść skargę do Prezesa Urzędu Ochrony Danych Osobowych
        (ul. Stawki 2, 00-193 Warszawa).
    </p>


    <h2>7. Pliki cookies</h2>

    <p>
        Sklep używa wyłącznie niezbędnych plików cookies: pliku sesji, który
        pamięta zawartość koszyka, oraz pliku zabezpieczającego formularze
        (XSRF-TOKEN). Wygasają po {{ config('session.lifetime') }} minutach
        bezczynności. Nie używamy
        cookies analitycznych ani reklamowych.
    </p>

    <p>
        Cookies możesz zablokować w ustawieniach przeglądarki, ale wtedy
        koszyk i formularz zamówienia nie będą działać.
    </p>


    <h2>8. Zmiany polityki prywatności</h2>

    <p>
        O zmianach tej polityki informujemy na tej stronie, podając datę,
        od której obowiązuje nowa wersja.
    </p>

</div>

</section>

@endsection
