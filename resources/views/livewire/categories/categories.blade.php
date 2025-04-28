<div>
    <ul class="space-y-2">
        @foreach ($categories as $category)
            @include('livewire.categories.category-item', ['category' => $category, 'selectedCategory' => $selectedCategory])
        @endforeach
    </ul>
</div>
