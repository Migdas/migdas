<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Sklep
|--------------------------------------------------------------------------
*/

Route::get('/', [StoreController::class, 'index'])
    ->name('store.index');

Route::get('/sklep', [StoreController::class, 'index'])
    ->name('store.products');

Route::get('/produkt/{product:slug}', [StoreController::class, 'show'])
    ->name('store.product');


/*
|--------------------------------------------------------------------------
| Koszyk
|--------------------------------------------------------------------------
*/

Route::get('/koszyk', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/koszyk/dodaj', [CartController::class, 'add'])
    ->name('cart.add');

Route::patch('/koszyk/{variant}', [CartController::class, 'update'])
    ->name('cart.update');

Route::delete('/koszyk/{variant}', [CartController::class, 'remove'])
    ->name('cart.remove');


/*
|--------------------------------------------------------------------------
| Checkout / zamówienie
|--------------------------------------------------------------------------
*/

Route::get('/zamowienie', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/zamowienie', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get(
    '/zamowienie/{order:number}/potwierdzenie',
    [CheckoutController::class, 'success']
)->name('checkout.success');
