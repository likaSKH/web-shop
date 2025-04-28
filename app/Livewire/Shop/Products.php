<?php

namespace App\Livewire\Shop;

use App\Livewire\Categories\Categories;
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
        \Log::info([$this->orderBy, $this->orderDirection] = explode('-', $value));
        [$this->orderBy, $this->orderDirection] = explode('-', $value);
        $this->resetPage();
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

        return view('livewire.shop.products', [
            'products' => $products
        ])->layout('layouts.app');
    }
}
