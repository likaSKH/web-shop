<div class="min-h-screen flex flex-col p-6 space-y-6 w-full max-w-5xl mx-auto"
    x-data="{
        showConfirmModal: false,
        selectedProductId: null,
        openOrderModal: false
    }"
>
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
    <div class="text-right text-sm text-gray-600">
        Balance: <span class="font-semibold text-gray-900">${{ number_format(auth()->user()->balance, 2) }}</span>
    </div>
    <div class="bg-white p-6 rounded-xl space-y-4 w-full max-w-4xl mx-auto shadow-lg">

        @if($cartItems->isEmpty())
            <p class="text-center text-gray-500">Your cart is empty.
                <a href="{{ route('products') }}" class="block font-bold text-teal-600 hover:text-teal-800 font-medium">
                    {{ __('Shop now') }}
                </a>
            </p>
        @else
            <ul class="space-y-4">
                @foreach($cartItems as $cartProduct)
                    <div class="flex flex-col space-y-2 border-b pb-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">{{ $cartProduct->product->name }}</span>
                            <div class="flex items-center gap-2">
                                <label class="text-sm text-gray-600">Quantity:</label>
                                <input
                                    type="number"
                                    min="1"
                                    max="{{ $cartProduct->product->quantity }}"
                                    wire:change="updateQuantity({{ $cartProduct->product_id }}, $event.target.value)"
                                    value="{{ $cartProduct->quantity }}"
                                    class="w-16 border rounded-md px-2 py-1 text-center"
                                />
                                <span class="text-xs text-gray-500">(Max: {{ $cartProduct->product->quantity }})</span>
                            </div>
                            <button
                                wire:click.prevent="confirmRemove({{ $cartProduct->product_id }})"
                                class="text-red-600 hover:underline"
                            >
                                Remove
                            </button>
                        </div>

                        <div class="flex justify-between items-center text-sm text-gray-700">
                            <span>Price: ${{ number_format($cartProduct->product->price, 2) }}</span>
                            <span>Total: ${{ number_format($cartProduct->product->price * $cartProduct->quantity, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </ul>

            <div class="pt-4 text-right">
                <span class="text-lg font-bold">Total: ${{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}</span>
            </div>
            <div class="pt-6 text-right">
                <button
                    wire:click.prevent="openOrderModal"
                    class="bg-blue-600 text-teal-900 font-semibold px-6 py-3 rounded-lg shadow-md border border-teal-700 hover:bg-teal-900 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                >
                    Order Now
                </button>
            </div>
        @endif
    </div>
    <x-confirm-removal-modal x-show="showConfirmModal">
        <x-slot:title>{{ __('Confirm Removal') }}</x-slot:title>
        <x-slot:message>{{ __('Do you really want to remove this item from your cart?') }}</x-slot:message>
        <x-slot:confirm>{{ __('Yes, remove it') }}</x-slot:confirm>
        <x-slot:cancel>{{ __('No, keep it') }}</x-slot:cancel>
    </x-confirm-removal-modal>

    <x-order-confirmation-modal x-show="openOrderModal"/>
</div>
