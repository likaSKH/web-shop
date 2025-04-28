<li>
    <div
        wire:click="$dispatchTo('shop.products','categorySelected', { categoryId: {{ $category->id }} })"
        class="cursor-pointer font-semibold text-gray-700
        hover:text-teal-800 flex justify-between items-center
        {{ $category->id == $selectedCategory ? 'text-teal-800' : '' }}
        "
    >
        <span>{{ $category->name }}</span>
        @if ($category->id == $selectedCategory)
            <span class="ml-2 text-teal-800">✓</span>
        @endif
    </div>

    @if ($category->subcategories->count())
        <ul class="ml-4 space-y-1 mt-1">
            @foreach ($category->subcategories as $subcategory)
                @include('livewire.categories.category-item', ['category' => $subcategory])
            @endforeach
        </ul>
    @endif
</li>
