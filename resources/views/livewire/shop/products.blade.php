<div class="min-h-screen flex flex-col p-6 space-y-6 w-full max-w-5xl mx-auto">

    @if (session()->has('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            class="bg-green-100 text-green-800 px-4 py-2 rounded-md mb-4"
        >
            {{ session('success') }}
            <button @click="show = false" class="ml-4 text-sm underline">Dismiss</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            class="bg-red-100 text-red-800 px-4 py-2 rounded-md mb-4"
        >
            {{ session('error') }}
            <button @click="show = false" class="ml-4 text-sm underline">Dismiss</button>
        </div>
    @endif
    <!-- Top Controls -->
    <div class="w-full flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <!-- Category Button with Dropdown -->
        <div x-data="{ categoryOpen: false, categoriesLoaded: false }"  class="relative">
            <button @click="
                categoryOpen = !categoryOpen;
                if (categoryOpen && !categoriesLoaded) {
                    categoriesLoaded = true;
                }
            " class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">
                Categories
            </button>

            <div x-show="categoryOpen" x-transition @click.outside="categoryOpen = false" x-cloak
                 class="absolute mt-2 w-56 bg-white shadow-lg rounded-md z-50 p-4 space-y-2">
                <div x-show="categoriesLoaded">
                    <livewire:categories.categories/>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="w-full md:w-1/2">
            <x-input
                name="query"
                wire:key="query"
                wire:model.live="query"
                placeholder="Search products..."
                class="p-4 w-full border-gray-300 focus:ring focus:ring-gray-200 rounded-md shadow-sm"
            />
        </div>

        <!-- Sort Dropdown -->
        <select
            wire:change="setOrder($event.target.value)"
            class="p-4 pr-8 border-gray-300 focus:ring focus:ring-gray-200 rounded-md shadow-sm"
        >
            <option value="name-asc">Name ↑</option>
            <option value="name-desc">Name ↓</option>
            <option value="price-asc">Price ↑</option>
            <option value="price-desc">Price ↓</option>
        </select>
    </div>

    <!-- Products Grid -->
    <div class="flex-grow">
        @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div
                        x-data="{
                            orderOpen: false,
                            quantity: 1,
                            total: {{ $product->price }},
                            price: {{ $product->price }},
                            available: {{ $product->quantity }}
                        }"
                        class="bg-white shadow-lg rounded-2xl p-8 flex flex-col items-center text-center space-y-4"
                    >
                        <div class="w-40 h-40 flex items-center justify-center bg-gray-100 rounded-xl text-gray-400">
                            <x-carbon-no-image class="w-12 h-12" />
                        </div>

                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $product->name }}
                        </h2>

                        <p class="text-teal-800 font-bold text-xl">
                            ${{ number_format($product->price, 2) }}
                        </p>

                        @if($product->quantity > 0)
                            <x-form-button @click.prevent="orderOpen = true" class="w-full">
                                Add to Cart
                            </x-form-button>
                        @else
                            <span class="text-red-500">Out of stock</span>
                        @endif

                        <template x-teleport="body">
                            <x-order-modal :product="$product" :user="$user" />
                        </template>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
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
