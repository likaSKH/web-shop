<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
    @foreach($products as $product)
        <div class="bg-white shadow-md rounded-2xl p-4 space-y-2 hover:shadow-lg transition">
            <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
            <p class="text-sm text-gray-600">{{ $product->description }}</p>
            <div class="text-blue-600 font-bold text-xl">${{ number_format($product->price, 2) }}</div>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add to Cart
            </button>
        </div>
    @endforeach
</div>
