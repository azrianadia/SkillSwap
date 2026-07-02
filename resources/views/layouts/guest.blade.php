<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KolaboKampus') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
            <div class="mb-8">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg flex items-center justify-center transform hover:scale-105 transition-transform duration-200">
                        <span class="text-3xl font-bold text-white">KK</span>
                    </div>
                    <h1 class="mt-4 text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">KolaboKampus</h1>
                    <p class="text-sm text-gray-600 mt-1">Platform Pertukaran Keahlian Mahasiswa</p>
                </div>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>
            
            <p class="mt-6 text-xs text-gray-500">&copy; {{ date('Y') }} KolaboKampus. All rights reserved.</p>
        </div>
    </body>
</html>
