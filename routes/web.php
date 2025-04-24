<?php

use App\Livewire\Shop\Products;
use Illuminate\Support\Facades\Route;

Route::get('/products', Products::class)->name('products');
