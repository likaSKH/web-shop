<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webshop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Vite for Laravel --}}
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900">
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="text-xl font-semibold text-gray-800 hover:text-indigo-600">
            MyShop
        </a>

        <!-- Nav -->
        <nav class="space-x-4">
            <a href="{{ route('products') }}" class="text-gray-700 hover:text-indigo-500">Products</a>

{{--            @auth--}}
{{--                <form method="POST" action="{{ route('logout') }}" class="inline">--}}
{{--                    @csrf--}}
{{--                    <button type="submit" class="text-gray-700 hover:text-red-500">Logout</button>--}}
{{--                </form>--}}
{{--            @else--}}
{{--                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-500">Login</a>--}}
{{--                <a href="{{ route('register') }}" class="text-gray-700 hover:text-indigo-500">Register</a>--}}
{{--            @endauth--}}
        </nav>
    </div>
</header>

<main class="py-6">
    {{ $slot }}
</main>

@livewireScripts
</body>
</html>
