<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Categories extends Component
{
    public $categories;
    public $expanded = [];
    public $selectedCategory;
    protected $listeners = [ 'category-updated' => 'updatedSelectedCategory' ];

    public function mount($selectedCategory = null)
    {
        $this->selectedCategory = $selectedCategory;
        $this->loadCategories();
    }

    public function updatedSelectedCategory($value)
    {
        $this->selectedCategory = $value;
    }

    protected function loadCategories()
    {
        $this->categories = Category::whereNull('parent_id')
            ->with('subcategories')
            ->get();
    }

    public function render()
    {
        return view('livewire.categories.categories', [
            'selectedCategory' => $this->selectedCategory
        ]);
    }
}
