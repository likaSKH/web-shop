<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="grid grid-cols-12 gap-4 w-full max-w-4xl px-4">

        <!-- Centered Logo and Card -->
        <div class="col-span-12 sm:col-start-4 sm:col-span-6 md:col-start-4 md:col-span-6 lg:col-start-4 lg:col-span-6 flex flex-col items-center">

            <div>
                <a href="/" wire:navigate>
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
                {{ $slot }}
            </div>

        </div>

    </div>
</div>
</body>
</html>
