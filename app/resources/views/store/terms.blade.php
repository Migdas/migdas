@extends('layouts.store')

@section('title', 'Regulamin sklepu - Migdas')

@section('description', 'Regulamin sklepu internetowego Migdas: zamówienia, płatności, dostawa, zwroty i reklamacje.')

@php
    $seller = config('shop.seller');

    // Fragmenty, które właściciel sklepu musi uzupełnić przed publikacją.
    $todo = fn (string $text) => new \Illuminate\Support\HtmlString(
        '<mark class="legal-todo">[DO UZUPEŁNIENIA: ' . e($text) . ']</mark>'
    );

    $address = $seller['address'] ?: $todo('adres sprzedawcy do korespondencji i zwrotów');
@endphp

@section('content')

<section class="legal-page">

<div class="container legal-content">

    <h1>Regulamin sklepu internetowego Migdas</h1>

    <p class="legal-meta">
        Obowiązuje od: {{ $todo('data wejścia w życie') }}
    </p>


    <h2>§ 1. Postanowienia ogólne</h2>

    <ol>
        <li>
            Sklep internetowy Migdas, dostępny pod adresem
            <a href="{{ url('/') }}">{{ preg_replace('#^https?://#', '', url('/')) }}</a>
            (dalej: „Sklep”), prowadzi {{ $seller['name'] }},
            {{ $address }}@if($seller['nip']), NIP {{ $seller['nip'] }}@else,
            {{ $todo('NIP i REGON albo informacja o prowadzeniu działalności nierejestrowanej') }}@endif
            (dalej: „Sprzedawca”).
        </li>
        <li>
            Kontakt ze Sprzedawcą:
            <ul>
                <li>e-mail: <a href="mailto:{{ $seller['email'] }}">{{ $seller['email'] }}</a>,</li>
                <li>telefon: <a href="tel:+48{{ preg_replace('/\D/', '', $seller['phone']) }}">{{ $seller['phone'] }}</a>
                    {{ $todo('godziny, w których telefon jest odbierany') }},</li>
                <li>adres korespondencyjny: {{ $address }}.</li>
            </ul>
        </li>
        <li>
            Regulamin określa zasady korzystania ze Sklepu, składania zamówień,
            zawierania i wykonywania umów sprzedaży, odstępowania od umów
            oraz składania reklamacji.
        </li>
        <li>
            Użyte w Regulaminie pojęcia oznaczają:
            <ul>
                <li><strong>Klient</strong> – osoba fizyczna posiadająca pełną zdolność
                    do czynności prawnych, osoba prawna albo jednostka organizacyjna
                    nieposiadająca osobowości prawnej, której ustawa przyznaje zdolność
                    prawną, składająca zamówienie w Sklepie;</li>
                <li><strong>Konsument</strong> – Klient będący osobą fizyczną, który
                    zawiera ze Sprzedawcą umowę niezwiązaną bezpośrednio z jego
                    działalnością gospodarczą lub zawodową;</li>
                <li><strong>Przedsiębiorca na prawach konsumenta</strong> – Klient będący
                    osobą fizyczną, który zawiera umowę bezpośrednio związaną z jego
                    działalnością gospodarczą, gdy z treści umowy wynika, że nie ma ona
                    dla niego charakteru zawodowego, wynikającego w szczególności
                    z przedmiotu wykonywanej działalności gospodarczej, udostępnionego
                    w CEIDG;</li>
                <li><strong>Towar</strong> – produkt prezentowany w Sklepie, w tym
                    produkt wykonywany w technologii druku 3D;</li>
                <li><strong>Umowa</strong> – umowa sprzedaży Towaru zawierana na odległość
                    między Sprzedawcą a Klientem za pośrednictwem Sklepu.</li>
            </ul>
        </li>
        <li>
            Sprzedaż odbywa się na terenie Rzeczypospolitej Polskiej.
        </li>
    </ol>


    <h2>§ 2. Usługi elektroniczne i wymagania techniczne</h2>

    <ol>
        <li>
            Sprzedawca świadczy nieodpłatnie drogą elektroniczną usługi polegające
            na umożliwieniu przeglądania Towarów, prowadzeniu koszyka oraz
            udostępnieniu formularza zamówienia. Usługi te są świadczone przez czas
            korzystania ze Sklepu, a umowa o ich świadczenie rozwiązuje się z chwilą
            opuszczenia Sklepu albo złożenia zamówienia.
        </li>
        <li>
            Do korzystania ze Sklepu potrzebne są: urządzenie z dostępem do
            Internetu, aktualna przeglądarka internetowa z włączoną obsługą plików
            cookies oraz aktywny adres e-mail.
        </li>
        <li>
            Klient zobowiązany jest do korzystania ze Sklepu zgodnie z prawem
            i dobrymi obyczajami, w szczególności do niepodawania danych
            nieprawdziwych lub danych osób trzecich bez ich zgody.
        </li>
    </ol>


    <h2>§ 3. Zamówienia i zawarcie umowy</h2>

    <ol>
        <li>
            Zamówienie składa się, dodając Towary do koszyka, wypełniając formularz
            zamówienia, akceptując Regulamin i klikając przycisk „Zamawiam”.
            Kliknięcie tego przycisku oznacza złożenie zamówienia z obowiązkiem
            zapłaty.
        </li>
        <li>
            Przed złożeniem zamówienia Klient widzi podsumowanie: wybrane Towary,
            ich cenę, koszt dostawy i łączną kwotę do zapłaty.
        </li>
        <li>
            Po złożeniu zamówienia Klient otrzymuje na podany adres e-mail
            potwierdzenie jego przyjęcia wraz z numerem zamówienia i danymi
            do płatności. Z chwilą otrzymania tej wiadomości Umowa zostaje zawarta.
        </li>
        <li>
            Treść zawartej Umowy, obejmująca zamówienie i niniejszy Regulamin,
            jest utrwalana i przekazywana Klientowi w wiadomości e-mail,
            o której mowa w ust. 3. {{ $todo('upewnić się, że e-mail z potwierdzeniem zamówienia zawiera Regulamin (np. w załączniku PDF) lub jego pełną treść') }}
        </li>
    </ol>


    <h2>§ 4. Ceny i płatności</h2>

    <ol>
        <li>
            Ceny Towarów podane w Sklepie są cenami końcowymi wyrażonymi w złotych
            polskich. Koszt dostawy jest podawany osobno w formularzu zamówienia
            i doliczany do łącznej kwoty zamówienia.
        </li>
        <li>
            Przy obniżce ceny Sklep podaje także najniższą cenę Towaru z okresu
            30 dni przed wprowadzeniem obniżki.
        </li>
        <li>
            Płatność odbywa się przelewem tradycyjnym na rachunek bankowy
            Sprzedawcy wskazany w potwierdzeniu zamówienia. W tytule przelewu
            należy podać numer zamówienia.
        </li>
        <li>
            Klient powinien opłacić zamówienie w terminie
            {{ $todo('liczba dni, np. 7') }} dni od jego złożenia.
            Jeżeli płatność nie wpłynie w tym terminie, Sprzedawca może wezwać
            Klienta do zapłaty w dodatkowym terminie, a po jego bezskutecznym
            upływie odstąpić od Umowy.
        </li>
        <li>
            Sprzedawca może wystawić paragon albo fakturę.
            {{ $todo('jaki dowód zakupu wydaje sprzedawca (paragon, faktura, rachunek) i jak Klient może poprosić o fakturę') }}
        </li>
    </ol>


    <h2>§ 5. Dostawa i czas realizacji</h2>

    <ol>
        <li>
            Towary są dostarczane wyłącznie przesyłką kurierską na adres
            w Polsce podany w zamówieniu. Sklep nie prowadzi odbioru osobistego.
        </li>
        <li>
            Koszt dostawy kurierskiej wynosi
            {{ number_format($courierCost, 2, ',', ' ') }} zł.
            Przesyłki realizuje {{ $todo('nazwa firmy kurierskiej') }}.
        </li>
        <li>
            Zamówienie jest przekazywane do realizacji po zaksięgowaniu wpłaty.
        </li>
        <li>
            Towary dostępne w magazynie są wysyłane w ciągu
            {{ $todo('liczba dni roboczych') }} dni roboczych od zaksięgowania wpłaty.
            Towary wykonywane na zamówienie (drukowane po złożeniu zamówienia)
            są wysyłane w ciągu {{ $todo('liczba dni roboczych') }} dni roboczych
            od zaksięgowania wpłaty. Do czasu realizacji należy doliczyć czas
            doręczenia przez kuriera, zwykle 1–2 dni robocze.
        </li>
        <li>
            Klient powinien w miarę możliwości sprawdzić przesyłkę przy odbiorze
            w obecności kuriera i w razie uszkodzenia spisać protokół szkody.
            Nie jest to warunkiem skorzystania z uprawnień z tytułu reklamacji.
        </li>
    </ol>


    <h2>§ 6. Charakter produktów drukowanych w 3D</h2>

    <ol>
        <li>
            Towary wykonywane są technologią druku 3D, warstwa po warstwie.
            Widoczne na powierzchni drobne linie warstw oraz niewielkie różnice
            odcienia koloru względem zdjęć wynikają z tej technologii i ustawień
            ekranu Klienta.
        </li>
        <li>
            Opis każdego Towaru określa jego materiał, kolor i przeznaczenie.
            Towar należy używać zgodnie z opisem, w szczególności z uwzględnieniem
            podanej odporności materiału na temperaturę i obciążenia.
        </li>
    </ol>


    <h2>§ 7. Prawo odstąpienia od umowy</h2>

    <ol>
        <li>
            Konsument może odstąpić od Umowy bez podawania przyczyny w terminie
            14 dni od dnia, w którym wszedł w posiadanie Towaru (lub w którym
            weszła w jego posiadanie wskazana przez niego osoba inna niż przewoźnik).
            Do zachowania terminu wystarczy wysłanie oświadczenia przed jego upływem.
        </li>
        <li>
            Oświadczenie o odstąpieniu można złożyć e-mailem na adres
            {{ $seller['email'] }} lub pisemnie na adres {{ $address }}.
            Można skorzystać ze wzoru formularza zamieszczonego na końcu Regulaminu,
            nie jest to jednak obowiązkowe.
        </li>
        <li>
            Sprzedawca niezwłocznie, nie później niż w terminie 14 dni od dnia
            otrzymania oświadczenia, zwraca Konsumentowi wszystkie dokonane
            płatności, w tym koszt dostawy (w wysokości odpowiadającej najtańszemu
            oferowanemu sposobowi dostawy), przy użyciu takiego samego sposobu
            płatności, jakiego użył Konsument, chyba że Konsument zgodził się na
            inny sposób niewiążący się dla niego z kosztami. Sprzedawca może
            wstrzymać się ze zwrotem płatności do chwili otrzymania Towaru
            z powrotem lub dostarczenia dowodu jego odesłania, w zależności
            od tego, które zdarzenie nastąpi wcześniej.
        </li>
        <li>
            Konsument odsyła Towar na adres {{ $address }} niezwłocznie, nie później
            niż w terminie 14 dni od dnia odstąpienia od Umowy. Bezpośrednie koszty
            zwrotu Towaru ponosi Konsument.
        </li>
        <li>
            Konsument odpowiada za zmniejszenie wartości Towaru wynikające
            z korzystania z niego w sposób wykraczający poza konieczny do
            stwierdzenia charakteru, cech i funkcjonowania Towaru.
        </li>
        <li>
            Prawo odstąpienia nie przysługuje w odniesieniu do Towaru
            nieprefabrykowanego, wyprodukowanego według specyfikacji Konsumenta
            lub służącego zaspokojeniu jego zindywidualizowanych potrzeb
            (np. produktu z personalizowanym napisem lub wykonanego według
            projektu przesłanego przez Konsumenta). Wybór materiału lub koloru
            spośród wariantów dostępnych w Sklepie nie wyłącza prawa odstąpienia.
        </li>
    </ol>


    <h2>§ 8. Reklamacje Towarów</h2>

    <ol>
        <li>
            Sprzedawca odpowiada wobec Konsumenta za brak zgodności Towaru z Umową
            istniejący w chwili jego dostarczenia i ujawniony w ciągu dwóch lat
            od tej chwili, na zasadach określonych w ustawie z dnia 30 maja 2014 r.
            o prawach konsumenta.
        </li>
        <li>
            Reklamację można złożyć e-mailem na adres {{ $seller['email'] }}
            lub pisemnie na adres {{ $address }}. Prosimy o podanie numeru
            zamówienia, opisu problemu (najlepiej ze zdjęciami) oraz oczekiwanego
            sposobu jego rozwiązania.
        </li>
        <li>
            Jeżeli Towar jest niezgodny z Umową, Konsument może żądać jego naprawy
            lub wymiany. Sprzedawca może dokonać wymiany, gdy Konsument żąda naprawy,
            lub naprawy, gdy Konsument żąda wymiany, jeżeli doprowadzenie do zgodności
            w sposób wybrany przez Konsumenta jest niemożliwe albo wymagałoby
            nadmiernych kosztów. Koszty naprawy lub wymiany, w tym koszty przesyłki,
            ponosi Sprzedawca.
        </li>
        <li>
            Konsument może złożyć oświadczenie o obniżeniu ceny albo odstąpieniu
            od Umowy, gdy Sprzedawca odmówił doprowadzenia Towaru do zgodności
            z Umową lub go nie doprowadził, gdy brak zgodności występuje nadal,
            gdy jest on na tyle istotny, że uzasadnia to bez wcześniejszej naprawy
            lub wymiany, albo gdy z oświadczenia Sprzedawcy lub okoliczności wynika,
            że nie doprowadzi on Towaru do zgodności z Umową w rozsądnym czasie
            lub bez nadmiernych niedogodności. Nie można odstąpić od Umowy,
            jeżeli brak zgodności jest nieistotny.
        </li>
        <li>
            Sprzedawca ustosunkuje się do reklamacji w terminie 14 dni od jej
            otrzymania. Brak odpowiedzi w tym terminie oznacza uznanie reklamacji.
        </li>
    </ol>


    <h2>§ 9. Reklamacje dotyczące działania Sklepu</h2>

    <ol>
        <li>
            Problemy z działaniem Sklepu można zgłaszać e-mailem na adres
            {{ $seller['email'] }}.
        </li>
        <li>
            Sprzedawca odpowiada na zgłoszenie w terminie 14 dni od jego otrzymania.
        </li>
    </ol>


    <h2>§ 10. Pozasądowe sposoby rozwiązywania sporów</h2>

    <ol>
        <li>
            Konsument może skorzystać z pozasądowych sposobów rozpatrywania
            reklamacji i dochodzenia roszczeń, w szczególności zwrócić się
            o pomoc do miejskiego lub powiatowego rzecznika konsumentów albo
            do wojewódzkiego inspektora Inspekcji Handlowej z wnioskiem
            o mediację lub o rozpatrzenie sprawy przez stały polubowny sąd
            konsumencki.
        </li>
        <li>
            Informacje o tych sposobach i procedurach są dostępne na stronie
            <a href="https://prawakonsumenta.uokik.gov.pl/" rel="noopener" target="_blank">prawakonsumenta.uokik.gov.pl</a>.
            Skorzystanie z nich jest dobrowolne i wymaga zgody obu stron.
        </li>
    </ol>


    <h2>§ 11. Przedsiębiorcy</h2>

    <ol>
        <li>
            Postanowienia § 7 i § 8 dotyczące Konsumenta stosuje się także
            do Przedsiębiorcy na prawach konsumenta.
        </li>
        <li>
            Wobec Klientów będących przedsiębiorcami, którzy nie są Przedsiębiorcami
            na prawach konsumenta, odpowiedzialność Sprzedawcy z tytułu rękojmi
            za wady jest wyłączona, a odpowiedzialność odszkodowawcza ograniczona
            jest do wysokości ceny zamówionego Towaru i kosztów dostawy.
        </li>
    </ol>


    <h2>§ 12. Dane osobowe</h2>

    <ol>
        <li>
            Administratorem danych osobowych Klientów jest Sprzedawca.
            Dane podane w formularzu zamówienia są przetwarzane w celu zawarcia
            i wykonania Umowy (art. 6 ust. 1 lit. b RODO), wypełnienia obowiązków
            prawnych, w tym podatkowych i rachunkowych (art. 6 ust. 1 lit. c RODO),
            oraz ewentualnego dochodzenia roszczeń (art. 6 ust. 1 lit. f RODO).
        </li>
        <li>
            Dane są przekazywane podmiotom, które pomagają w realizacji zamówienia,
            w szczególności firmie kurierskiej, dostawcy hostingu i dostawcy
            usługi wysyłki wiadomości e-mail.
        </li>
        <li>
            Szczegółowe informacje o przetwarzaniu danych osobowych, prawach
            Klienta i plikach cookies zawiera Polityka prywatności:
            {{ $todo('link do polityki prywatności') }}.
        </li>
    </ol>


    <h2>§ 13. Postanowienia końcowe</h2>

    <ol>
        <li>
            Regulamin jest dostępny nieodpłatnie na tej stronie w formie,
            która umożliwia jego pobranie, utrwalenie i wydrukowanie.
        </li>
        <li>
            Sprzedawca może zmienić Regulamin z ważnych przyczyn, w szczególności
            zmiany przepisów prawa lub sposobu prowadzenia Sklepu. Do zamówień
            złożonych przed zmianą stosuje się Regulamin w dotychczasowym brzmieniu.
        </li>
        <li>
            W sprawach nieuregulowanych Regulaminem stosuje się przepisy prawa
            polskiego, w szczególności Kodeksu cywilnego i ustawy o prawach konsumenta.
            Regulamin nie wyłącza ani nie ogranicza praw Konsumenta wynikających
            z bezwzględnie obowiązujących przepisów.
        </li>
    </ol>


    <h2 id="formularz-odstapienia">Załącznik – wzór formularza odstąpienia od umowy</h2>

    <div class="legal-form">
        <p><em>(formularz należy wypełnić i odesłać tylko w przypadku chęci odstąpienia od umowy)</em></p>

        <p>
            Adresat: {{ $seller['name'] }}, {{ $address }},
            e-mail: {{ $seller['email'] }}
        </p>

        <p>
            Ja/My(*) niniejszym informuję/informujemy(*) o moim/naszym odstąpieniu
            od umowy sprzedaży następujących rzeczy(*) / umowy dostawy następujących
            rzeczy(*) / umowy o dzieło polegającej na wykonaniu następujących
            rzeczy(*) / o świadczenie następującej usługi(*):
        </p>

        <p>…………………………………………………………</p>
        <p>Data zawarcia umowy(*) / odbioru(*): ……………………</p>
        <p>Numer zamówienia: ……………………</p>
        <p>Imię i nazwisko konsumenta(-ów): ……………………</p>
        <p>Adres konsumenta(-ów): ……………………</p>
        <p>Podpis konsumenta(-ów) (tylko jeżeli formularz jest przesyłany w wersji papierowej): ……………………</p>
        <p>Data: ……………………</p>

        <p><small>(*) Niepotrzebne skreślić.</small></p>
    </div>

</div>

</section>

@endsection
