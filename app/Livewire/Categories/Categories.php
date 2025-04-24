<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Categories extends Component
{
    public $categories;
    public $expanded = [];

    public function mount()
    {
        $this->loadCategories();
    }

    public function updatedSearchQuery()
    {
        $this->loadCategories();
    }

    protected function loadCategories()
    {
        $this->categories = Category::whereNull('parent_id')
            ->with('subcategories')
            ->get();
    }

    public function render()
    {
        return view('livewire.categories.categories');
    }
}
