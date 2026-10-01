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
            Wartość zamówienia:
            <strong>
                {{ number_format(
                    $order->total,
                    2,
                    ',',
                    ' '
                ) }} zł
            </strong>
        </p>

        <a
            href="{{ route('store.index') }}"
            class="button">

            Wróć do sklepu

        </a>

    </div>

</div>

</section>

@endsection
