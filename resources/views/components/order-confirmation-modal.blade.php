<div
    x-data="{ openOrderModal: false }"
    x-init="
        window.addEventListener('open-order-modal', () => {
            openOrderModal = true
        });
        window.addEventListener('close-order-modal', () => {
            openOrderModal = false
        });
    "
>
<template x-teleport="body">
    <div
        x-show="openOrderModal"
        x-transition
        @click.outside="openOrderModal = false"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white p-6 rounded-xl w-96 space-y-4 shadow-xl">
            <h2 class="text-lg font-bold text-gray-800 text-center">
                Confirm Order
            </h2>
            <p class="text-center text-gray-600">
                Are you sure you want to place this order?
            </p>
            <div class="flex justify-end space-x-2 pt-4">
                <button
                    @click="openOrderModal = false"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md"
                >
                    Cancel
                </button>
                <button
                    wire:click="placeOrder"
                    class="flex-1 px-4 py-2 rounded-md bg-teal-600 hover:bg-red-700 text-white"
                >
                    Yes, Order
                </button>
            </div>
        </div>
    </div>
</template>
</div>
