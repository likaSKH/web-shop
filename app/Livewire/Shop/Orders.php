<?php

namespace App\Livewire\Shop;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Orders extends Component
{
    public $orders;

    public function mount()
    {
        $user = Auth::user();

        if(!$user) {
            return redirect()->route('products');
        }

        $this->orders = $user->orders()->with('orderProducts.product')
            ->withTrashed()
            ->latest()->get();
    }

    public function cancelOrder($orderId)
    {
        $order = Order::with('orderProducts.product')->findOrFail($orderId);

        $user = auth()->user();
        $isOwner = $order->user_id === $user->id;

        if (!($isOwner)) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            session()->flash('error', 'Only pending orders can be canceled.');
            return;
        }

        foreach ($order->orderProducts as $item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        $user->increment('balance', $order->orderProducts->sum(fn($item) => $item->price * $item->quantity));

        $order->update(['status' => OrderStatus::Canceled->value]);
        $order->delete();

        session()->flash('success', 'Order canceled and refunded.');

        return redirect()->route('orders');
    }

    public function render()
    {
        return view('livewire.shop.orders')->layout('layouts.app');
    }
}
