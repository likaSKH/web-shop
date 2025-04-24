<?php

namespace App\Livewire\Shop;

use Livewire\Component;

class Products extends Component
{
    public $products;

    public function mount()
    {
        $this->products = \App\Models\Product::all();
    }

    public function order($productId)
    {
        // Optional: redirect to order form or show a modal
    }

    public function render()
    {
        return view('livewire.shop.products')->layout('layouts.app'); ;
    }
}
