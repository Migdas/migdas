@extends('layouts.store')

@section('title', 'Zbyt wiele prób - Migdas')

@section('content')

<section class="order-success">

<div class="container">

    <div class="success-card">

        <h1>
            Zbyt wiele prób
        </h1>

        <p>
            Odczekaj chwilę i spróbuj ponownie. W razie problemów napisz na
            <a href="mailto:{{ config('shop.seller.email') }}">{{ config('shop.seller.email') }}</a>.
        </p>

    </div>

</div>

</section>

@endsection
