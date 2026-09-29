<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'index'])
    ->name('store.index');

Route::get('/sklep', [StoreController::class, 'index'])
    ->name('store.products');

Route::get('/produkt/{product:slug}', [StoreController::class, 'show'])
    ->name('store.product');
