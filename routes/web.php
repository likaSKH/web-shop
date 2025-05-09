<?php

use App\Livewire\Shop\Cart;
use App\Livewire\Shop\Products;
use App\Livewire\Shop\Orders;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/', Products::class)->name('products');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/orders', Orders::class)->name('orders');

Route::view('account', 'account')
    ->middleware(['auth', 'verified'])
    ->name('account');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


require __DIR__ . '/auth.php';
