<li>
    <div class="cursor-pointer font-semibold text-gray-700 hover:text-teal-800 flex justify-between items-center">
        <span>{{ $category->name }}</span>
    </div>

    @if ($category->subcategories->count())
        <ul class="ml-4 space-y-1 mt-1">
            @foreach ($category->subcategories as $subcategory)
                @include('livewire.categories.category-item', ['category' => $subcategory])
            @endforeach
        </ul>
    @endif
</li>
