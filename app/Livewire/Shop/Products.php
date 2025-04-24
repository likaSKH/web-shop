<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $perPage = 10;  // Number of products per page

    // Load products with pagination
    public function render()
    {
        $products = Product::paginate($this->perPage);  // Paginate the products

        return view('livewire.shop.products', [
            'products' => $products,  // Pass paginated products to the view
        ]);
    }
}
