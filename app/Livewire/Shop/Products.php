<?php

namespace App\Livewire\Shop;

use App\Livewire\Categories\Categories;
use App\Models\Order;
use App\Models\Product;
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

    public function placeOrder($productId, $quantity = 1)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($productId);

        if ($quantity < 1 || $quantity > $product->quantity) {
            session()->flash('error', 'Product quantity is insufficient.');
            return;
        }

        $total = $product->price * $quantity;

        if ($total > auth()->user()->balance) {
            session()->flash('error', 'Insufficient balance');
            return;
        }

        $user = auth()->user();

        Order::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'product_id' => $productId,
            'quantity' => $quantity,
            'total' => $total,
        ]);

        $product->decrement('quantity', $quantity);

        auth()->user()->decrement('balance', $total);

        session()->flash('success', 'Order was created successfully.');
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
