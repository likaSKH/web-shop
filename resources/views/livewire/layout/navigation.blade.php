<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
};
?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('products')" :active="request()->routeIs('products')" wire:navigate>
                        {{ __('Products') }}
                    </x-nav-link>

                    @if(auth()->user()?->name)
                        <x-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-nav-link>
                        <x-nav-link :href="route('orders')">
                            {{ __('Orders') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="relative flex items-center pr-8">
                        <a href="{{ route('cart') }}" class="relative">
                            <x-bi-cart-fill class="w-7 h-7 text-gray-700" />

                            <span class="absolute -top-1/2  ml-4 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-teal-600 rounded-full">
                                @livewire('shop.cart-count')
                            </span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button wire:click="logout" class="text-gray-600 hover:text-gray-800 font-medium">
                            {{ __('Log Out') }}
                        </button>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('products')" :active="request()->routeIs('products')" wire:navigate>
                {{ __('Products') }}
            </x-responsive-nav-link>
            @if(auth()->user()?->name)
                <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart')" :active="request()->routeIs('profile')" wire:navigate>
                    {{ __('Cart') }}
                    <span class="py-1 px-1 text-xs font-bold text-white bg-teal-600 rounded-full">
                        @livewire('shop.cart-count')
                    </span>
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800"
                         x-data="{{ json_encode(['name' => auth()->user()?->name ?? 'Guest']) }}"
                         x-text="name"
                         x-on:profile-updated.window="name = $event.detail.name">
                    </div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()?->email ?? '' }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            @else
                <div class="px-4 space-y-2">
                    <a href="{{ route('login') }}" class="block text-gray-600 hover:text-gray-800 font-medium">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="block text-gray-600 hover:text-gray-800 font-medium">
                        {{ __('Register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
