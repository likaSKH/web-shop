<?php

use App\Livewire\Shop\Products;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/products', Products::class)->name('products');
