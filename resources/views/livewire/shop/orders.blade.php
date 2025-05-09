<div class="max-w-5xl mx-auto p-6 space-y-6">
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
    <h1 class="text-2xl font-bold mb-4">My Orders</h1>

    @forelse ($orders as $order)
        <div class="bg-white rounded-xl p-4 shadow-md space-y-2">
            <div class="flex justify-between items-center border-b pb-2">
                <span class="font-semibold text-gray-700">Order #{{ $order->id }}</span>
                <span class="ml-2 px-2 py-1 rounded-full text-lg
                    {{
                        $order->status === 'pending' ? 'text-indigo-600' :
                        ($order->status === 'completed' ? 'text-green-600' :
                        ($order->status === 'canceled' ? 'text-red-500' : ''))
                    }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</span>
            </div>

            @foreach ($order->orderProducts as $item)
                <div class="flex justify-between text-sm text-gray-600 border-b py-1">
                    <div>
                        {{ $item->product->name ?? 'Deleted Product' }} x {{ $item->quantity }}
                    </div>
                    <div>
                        ${{ number_format($item->price * $item->quantity, 2) }}
                    </div>
                </div>
            @endforeach

            <div class="text-right font-bold pt-2">
                Total: ${{ number_format($order->orderProducts->sum(fn($i) => $i->price * $i->quantity), 2) }}
            </div>

            @if(
                !$order->trashed()
                && $order->status === 'pending')
                <div class="text-right pt-4">
                    <button
                        x-data="{ confirm: false }"
                        x-on:click="confirm ? $wire.cancelOrder({{ $order->id }}) : confirm = true"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200"
                    >
                        <span x-show="!confirm">Cancel Order</span>
                        <span x-show="confirm">Are you sure? Click again.</span>
                    </button>
                </div>
            @endif
        </div>
    @empty
        <p class="text-gray-500 text-center">You have no orders yet.</p>
    @endforelse
</div>
