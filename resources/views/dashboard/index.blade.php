<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900">KolaboKampus</h1>
                </div>
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">Dashboard</a>
                    <a href="{{ route('swaps.index') }}" class="text-gray-700 hover:text-gray-900">My Swaps</a>
                    <a href="{{ route('chat.index') }}" class="flex items-center space-x-1 text-gray-700 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h4m4 0h4m-8 4h4m-4 0h4m-4-8h4m-4 0h4m0-4h4m-4 0h4" />
                        </svg>
                        <span>Pesan</span>
                    </a>
                    
                    <!-- Notification Bell -->
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.001 6.001 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadNotifications > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-gray-900">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-gray-900" onclick="return confirm('Yakin ingin keluar?')">Logout</button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Daftar User</h2>
            <p class="text-gray-600 mt-1">Temukan partner pertukaran keahlian</p>
        </div>

        <!-- Search & Filter Form -->
        <div class="mb-6 bg-white rounded-lg shadow p-6">
            <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search by Name -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nama user..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Filter by Skill -->
                    <div>
                        <label for="skill_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Skill</label>
                        <select id="skill_id" name="skill_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Skill --</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->id }}" {{ request('skill_id') == $skill->id ? 'selected' : '' }}>
                                    {{ $skill->skill_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Skill Type -->
                    <div>
                        <label for="skill_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Skill</label>
                        <select id="skill_type" name="skill_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Tipe --</option>
                            <option value="offer" {{ request('skill_type') == 'offer' ? 'selected' : '' }}>Ditawarkan</option>
                            <option value="seek" {{ request('skill_type') == 'seek' ? 'selected' : '' }}>Dicari</option>
                        </select>
                    </div>

                    <!-- Filter by Prodi -->
                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                        <input type="text" id="prodi" name="prodi" value="{{ request('prodi') }}" placeholder="Prodi..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if ($users->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500">Belum ada user terdaftar.</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($users as $user)
                    <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border-t-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                    @if($user->reviewsReceived->count() > 0)
                                        <div class="flex items-center space-x-1">
                                            @php
                                                $avgRating = round($user->getAverageRating(), 1);
                                                $fullStars = floor($avgRating);
                                                $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $fullStars)
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endif
                                            @endfor
                                            <span class="text-xs text-gray-600 ml-1">({{ $avgRating }})</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Belum ada rating</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($user->prodi || $user->semester)
                            <div class="mb-4 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                @if ($user->prodi)
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span class="font-medium">{{ $user->prodi }}</span>
                                    </p>
                                @endif
                                @if ($user->semester)
                                    <p class="flex items-center mt-1">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Semester {{ $user->semester }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        @if ($user->offeredSkills->isNotEmpty())
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-green-700 mb-2">Menawarkan:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->offeredSkills as $skill)
                                        <span class="inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            {{ $skill->skill_name }}
                                            @if ($skill->pivot->proficiency_level)
                                                <span class="ml-1 opacity-75">({{ $skill->pivot->proficiency_level }})</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($user->soughtSkills->isNotEmpty())
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-blue-700 mb-2">Mencari:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->soughtSkills as $skill)
                                        <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            {{ $skill->skill_name }}
                                            @if ($skill->pivot->proficiency_level)
                                                <span class="ml-1 opacity-75">({{ $skill->pivot->proficiency_level }})</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($user->offeredSkills->isEmpty() && $user->soughtSkills->isEmpty())
                            <p class="text-sm text-gray-500 italic">Belum menambahkan keahlian</p>
                        @endif

                        <div class="pt-4 border-t border-gray-100">
                            <a href="{{ route('users.show', $user->id) }}" class="block text-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                Lihat Profil →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $users->links() }}
        @endif
    </main>
</body>
</html>