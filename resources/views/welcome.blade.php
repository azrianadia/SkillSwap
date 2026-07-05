<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KolaboKampus - Platform Kolaborasi Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="container mx-auto px-6 py-4">
            <nav class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">KK</span>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">KolaboKampus</span>
                </div>
                
                @if (Route::has('login'))
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-semibold">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 hover:text-blue-600 font-medium transition-colors">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 font-semibold">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-6">
                        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                            Tukar Keahlian,<br/>
                            <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Raih Skill Baru</span>
                        </h1>
                        <p class="text-xl text-gray-600 leading-relaxed">
                            Platform pertukaran keahlian untuk mahasiswa. Tawarkan skill yang kamu kuasai, dapatkan skill yang kamu butuhkan.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:shadow-2xl transition-all duration-200 font-bold text-center">
                                Mulai Sekarang →
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-gray-700 rounded-xl hover:shadow-xl transition-all duration-200 font-semibold text-center border-2 border-gray-200">
                                Login
                            </a>
                        </div>

                        <!-- Value Props -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-8">
                            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-1">Tawarkan Skillmu</h3>
                                <p class="text-sm text-gray-600">Bagikan keahlian yang kamu kuasai ke teman lain</p>
                            </div>
                            
                            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-1">Cari Skill Baru</h3>
                                <p class="text-sm text-gray-600">Temukan teman yang bisa mengajarkan skill yang kamu butuhkan</p>
                            </div>
                            
                            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900 mb-1">Bertukar Fair</h3>
                                <p class="text-sm text-gray-600">Sistem swap timbal balik, saling menguntungkan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Illustration -->
                    <div class="hidden md:block">
                        <div class="relative">
                            <!-- Card 1 -->
                            <div class="absolute top-0 left-0 w-64 bg-white rounded-2xl shadow-2xl p-6 transform rotate-6 hover:rotate-0 transition-transform duration-300">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">PHP Developer</p>
                                        <p class="text-xs text-gray-500">Offered</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="absolute top-32 left-32 w-64 bg-white rounded-2xl shadow-2xl p-6 transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 014 4v4h2a4 4 0 014 4v2H4v-2a4 4 0 014-4V8a4 4 0 014-4zm4 10v2h2v-2h-2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">UI/UX Design</p>
                                        <p class="text-xs text-gray-500">Seeking</p>
                                    </div>
                                </div>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-medium">Intermediate</span>
                            </div>

                            <!-- Card 3 -->
                            <div class="absolute top-64 left-12 w-64 bg-white rounded-2xl shadow-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.533 1.533 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a4 4 0 110-8 4 4 0 010 8z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Swap Success!</p>
                                        <p class="text-xs text-green-600">✓ Completed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="container mx-auto px-6 py-8">
            <div class="text-center text-gray-600 text-sm">
                <p>&copy; {{ date('Y') }} KolaboKampus. Platform Kolaborasi Mahasiswa.</p>
            </div>
        </footer>
    </div>
</body>
</html>