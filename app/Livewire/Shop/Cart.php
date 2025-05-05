<?php

namespace App\Livewire\Shop;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $productToRemove = null;

    public function mount()
    {
        if(!auth()->user()) {
            return redirect()->route('products');
        }

        $this->loadCart();
    }

    public function loadCart()
    {
        $user = auth()->user();
        if ($user) {
            $cart = $user->cart()->first();
            $this->cartItems = $cart ? $cart->cartProducts : [];
        }
    }

    public function removeItem()
    {
        $user = auth()->user();
        $cart = $user->cart()->first();

        $cartProduct = $cart->cartProducts()->where('product_id', $this->productToRemove)->first();
        if ($cartProduct) {
            $cartProduct->delete();
            $this->loadCart();
        }

        $this->dispatch('close-remove-modal');
    }

    public function confirmRemove($productId)
    {
        $this->productToRemove = $productId;

        $this->dispatch('open-remove-modal');
    }

    public function cancelRemove()
    {
        $this->productToRemove = null;

        $this->dispatch('close-remove-modal');
    }

    public function openOrderModal()
    {
        $this->dispatch('open-order-modal');
    }

    public function closeModals()
    {
        $this->dispatch('close-remove-modal');
        $this->dispatch('close-order-modal');
    }

    public function render()
    {
        return view('livewire.shop.cart')->layout('layouts.app');;
    }

    public function updateQuantity($productId, $newQuantity)
    {
        $user = auth()->user();
        $cart = $user->cart;

        $cartProduct = $cart->cartProducts()->where('product_id', $productId)->first();

        if (!$cartProduct) return;

        $product = $cartProduct->product;

        if ($newQuantity > $product->quantity) {
            session()->flash('error', 'Requested quantity exceeds available stock.');
            $this->closeModals();
            return;
        }

        $cartProduct->update([
            'quantity' => $newQuantity
        ]);

        $cart->update([
            'total' => $cart->cartProducts()->sum(DB::raw('quantity * price')),
        ]);

        $this->loadCart();
    }

    public function placeOrder()
    {
        $user = auth()->user();
        $cart = $user->cart;

        $total = $cart->cartProducts->sum(fn($item) => $item->product->price * $item->quantity);

        if ($user->balance < $total) {
            session()->flash('error', 'Insufficient balance.');
            $this->closeModals();
            return;
        }

        if ($cart->cartProducts->isEmpty()) {
            session()->flash('error', 'Cart is empty.');
            $this->closeModals();
            return;
        }

        DB::transaction(function () use ($cart, $user) {
            $total = $cart->cartProducts->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $order = $user->orders()->create([
                'total' => $total,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ]);

            foreach ($cart->cartProducts as $item) {
                $order->products()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ]);

                $item->product->decrement('quantity', $item->quantity);
            }

            $cart->cartProducts()->delete();
            $cart->update(['total' => 0]);
        });

        $this->loadCart();
        $this->closeModals();

        session()->flash('success', 'Order placed successfully!');
    }
}
