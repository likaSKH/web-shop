<div class="p-6 space-y-6">
    @include('livewire.shop.products-header')

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
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

    <!-- Pagination Links -->
    <div class="flex justify-center mt-6 ">
        {{ $products->links('pagination::tailwind') }}
    </div>
</div>
