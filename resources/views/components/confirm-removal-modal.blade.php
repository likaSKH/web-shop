<div
    x-data="{ showConfirmModal: false }"
    x-init="
        window.addEventListener('open-remove-modal', () => {
            showConfirmModal = true
        });
        window.addEventListener('close-remove-modal', () => {
            showConfirmModal = false
        });
    "
>
    <template x-teleport="body">
        <div
            x-show="showConfirmModal"
            x-transition
            @click.outside="showConfirmModal = false"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-cloak
        >
            <div class="bg-white p-6 rounded-xl space-y-4 w-80 shadow-xl">
                <h2 class="text-lg font-bold text-gray-800 text-center">
                    {{ $title ?? __('Remove Item') }}
                </h2>

                <p class="text-center text-gray-600">
                    {{ $message ?? __('Are you sure you want to remove this product from your cart?') }}
                </p>

                <div class="flex space-x-2 pt-4">
                    <button
                        @click="showConfirmModal = false; $wire.cancelRemove()"
                        class="flex-1 px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-gray-800"
                    >
                        {{ $cancel ?? __('Cancel') }}
                    </button>

                    <button
                        wire:click="removeItem"
                        class="flex-1 px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white"
                    >
                        {{ $confirm ?? __('Remove') }}
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
