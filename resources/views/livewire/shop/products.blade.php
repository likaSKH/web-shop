<div class="min-h-screen flex flex-col p-6 space-y-6">
    <div class="w-full flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div x-data="{ open: false, categoriesLoaded: false }" class="relative">
            <button @click="
                open = !open;
                if (open && !categoriesLoaded) {
                    categoriesLoaded = true;
                }
            " class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">
                Categories
            </button>

            <div x-show="open" x-transition @click.away="open = false"
                 class="absolute mt-2 w-56 bg-white shadow-lg rounded-md z-50 p-4 space-y-2">
                <div x-show="categoriesLoaded">
                    @livewire('categories.categories')
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2">
            <x-input
                name="query"
                wire:key="query"
                wire:model.live="query"
                placeholder="Search products..."
                class="p-4 w-full border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 rounded-md shadow-sm"
            />
        </div>
    </div>

    <div class="flex-grow">
        @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white shadow-lg rounded-2xl p-8 flex flex-col items-center text-center space-y-4">
                        <div class="w-40 h-40 flex items-center justify-center bg-gray-100 rounded-xl text-gray-400">
                            <x-carbon-no-image class="w-12 h-12" />
                        </div>

                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $product->name }}
                        </h2>

                        <p class="text-teal-800 font-bold text-xl">
                            ${{ number_format($product->price, 2) }}
                        </p>

                        <x-form-button wire:click="addToCart({{ $product->id }})" class="w-full">
                            Add to Cart
                        </x-form-button>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="flex justify-center items-center min-h-[calc(100vh-12rem)] text-gray-500 text-lg">
                No products found...
            </div>
        @endif
    </div>
</div>
