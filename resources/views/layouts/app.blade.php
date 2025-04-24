<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webshop</title>

    {{-- Vite for Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="text-xl font-semibold text-gray-800 hover:text-teal-800 flex items-center space-x-2">
            <x-carbon-shopping-cart class="w-6 h-6 text-teal-800" />
            <span>MyShop</span>
        </a>

        <!-- Navigation -->
        <nav class="space-x-4">
            <a href="{{ route('products') }}" class="text-gray-700 hover:text-teal-800">Products</a>

            {{-- Authenticated routes --}}
            {{-- @auth --}}
            {{--     <form method="POST" action="{{ route('logout') }}" class="inline"> --}}
            {{--         @csrf --}}
            {{--         <x-form-button class="text-gray-700 hover:text-red-500 px-2 py-1 bg-transparent border-none"> --}}
            {{--             Logout --}}
            {{--         </x-form-button> --}}
            {{--     </form> --}}
            {{-- @else --}}
            {{--     <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-500">Login</a> --}}
            {{--     <a href="{{ route('register') }}" class="text-gray-700 hover:text-indigo-500">Register</a> --}}
            {{-- @endauth --}}
        </nav>
    </div>
</header>

<main class="py-6 flex justify-center items-center min-h-[calc(100vh-4rem)] px-4">
    {{ $slot }}
</main>

{{-- Livewire Scripts --}}
@livewireScripts

</body>
</html>
