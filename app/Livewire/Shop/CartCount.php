<?php

namespace App\Livewire\Shop;

use Livewire\Component;

class CartCount extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        $user = auth()->user();
        if ($user) {
            $cart = $user->cart()->first();
            $this->cartCount = $cart ? $cart->cartProducts->sum('quantity') : 0;
        }
    }

    public function render()
    {
        return view('livewire.shop.cart-count');
    }
}
