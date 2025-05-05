@props([
    'product',
    'user',
])

<div
    x-data="{
        quantity: 1,
        price: {{ $product->price }},
        available: {{ $product->quantity }},
        total: {{ $product->price }},
        productId: {{ $product->id }},
        userBalance: {{ json_encode($user?->balance) }},
        }"
    x-show="orderOpen"
    x-transition
    @click.outside="orderOpen = false"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;"
>
    <div class="bg-white p-6 rounded-xl space-y-4 w-80" @click.away="orderOpen = false">
        @if($user)
            <h2 class="text-lg font-bold text-gray-800 text-center">{{ $product->name }}</h2>

            <div class="flex flex-col space-y-2">
                <label class="text-gray-600">
                    Quantity (Available: {{ $product->quantity }})
                </label>
                <input
                    type="number"
                    min="1"
                    :max="available"
                    x-model.number="quantity"
                    @input="total = quantity * price"
                    class="border-gray-300 focus:ring focus:ring-teal-200 rounded-md"
                />
            </div>

            <div class="text-gray-700">
                Total:
                <span class="font-bold text-teal-700" x-text="`$${(total).toFixed(2)}`"></span>
            </div>

            <div class="text-gray-500 text-sm">
                Your balance: ${{ number_format($user->balance, 2) }}
            </div>

            <div class="flex space-x-2 pt-4">
                <button
                    @click="orderOpen = false"
                    class="flex-1 px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-gray-800"
                >
                    Cancel
                </button>

                <button
                    @click.prevent="
                        if (quantity > 0 && quantity <= available) {
                            $wire.call('addToCart', productId, quantity);
                            orderOpen = false;
                        }
                    "
                    class="flex-1 px-4 py-2 rounded-md bg-teal-600 hover:bg-teal-700 text-white"
                    :disabled="quantity < 1 || quantity > available"
                    >
                    Confirm
                </button>

            </div>
        @else
            <span class="text-center block text-gray-700">Please log in to place an order.</span>
            <a href="{{ route('login') }}"
               class="block mt-4 text-center text-teal-600 hover:underline">
                Go to Login
            </a>
        @endif
    </div>
</div>
