@extends('layouts.store')

@section('title', 'Regulamin sklepu - Migdas')

@section('description', 'Regulamin sklepu internetowego Migdas: zamówienia, płatności, dostawa, zwroty i reklamacje.')

@section('content')

<section class="legal-page">

<div class="container legal-content">

    @include('store.partials.terms-content')

</div>

</section>

@endsection
