
<div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <!-- Categories Dropdown -->
        <div x-data="{ open: false, categoriesLoaded: false }">
            <!-- Categories Button -->
            <button @click="
        open = !open;
        if (open && !categoriesLoaded) {
            categoriesLoaded = true;
        }
    " class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">
                Categories
            </button>

            <!-- Categories Dropdown -->
            <div x-show="open" x-transition @click.away="open = false" class="absolute mt-2 w-56 bg-white shadow-lg rounded-md z-50 p-4 space-y-2">
                <!-- Load Livewire Component dynamically -->
                <div x-show="categoriesLoaded">
                    @livewire('categories.categories')
                </div>
            </div>
        </div>

        <!-- Product Search -->
        <div class="w-full md:w-1/2">
            <input type="text"
                   wire:model.live.debounce.300ms="searchQuery"
                   placeholder="Search products..."
                   class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-600">
        </div>
</div>
