<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $query = '';

    public function render()
    {
        $products = Product::query()
            ->when($this->query, function ($query) {
                $query->where('name', 'like', '%' . $this->query . '%');
            })
            ->paginate($this->perPage);

        return view('livewire.shop.products', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}
