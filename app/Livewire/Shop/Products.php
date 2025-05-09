<?php

namespace App\Livewire\Shop;

use App\Livewire\Categories\Categories;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $query = '';
    public $selectedCategory = null;
    public $orderBy = 'name';
    public $orderDirection = 'asc';

    protected $listeners = ['categorySelected'];

    public function updatedOrderBy()
    {
        $this->resetPage();
    }

    public function updatedOrderDirection()
    {
        $this->resetPage();
    }

    public function categorySelected($categoryId)
    {
        $this->selectedCategory = $categoryId == $this->selectedCategory ? null : $categoryId;
        $this->dispatch('category-updated', $this->selectedCategory)->to(Categories::class);
        $this->resetPage();
    }

    public function setOrder($value)
    {
        [$this->orderBy, $this->orderDirection] = explode('-', $value);
        $this->resetPage();
    }

    public function addToCart($productId, $quantity = 1)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($productId);
        $user = auth()->user();

        if ($quantity < 1 || $quantity > $product->quantity) {
            session()->flash('error', 'Invalid quantity selected.');
            return;
        }

        $cart = $user->cart()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        $cartProduct = $cart->cartProducts()->where('product_id', $productId)->first();

        if ($cartProduct) {
            $cartProduct->update([
                'quantity' => $cartProduct->quantity + $quantity,
                'price' => $product->price,
            ]);
        } else {
            $cart->cartProducts()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        $cart->update([
            'total' => $cart->cartProducts()->sum(DB::raw('quantity * price')),
        ]);

        session()->flash('success', 'Product added to cart successfully.');
        return redirect()->route('products');
    }


    public function render()
    {
        $products = Product::query()
            ->when($this->selectedCategory, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->selectedCategory);
                });
            })
            ->when($this->query, function ($query) {
                $query->where('name', 'like', '%' . $this->query . '%');
            })
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);
        $user = auth()->user();

        return view('livewire.shop.products', compact('products', 'user'))->layout('layouts.app');
    }

}
