<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'SkillSwap') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">SkillSwap</h1>
                </div>
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Dashboard</a>
                    @auth
                        <a href="{{ route('logout') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Logout</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar User</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Temukan partner pertukaran keahlian</p>
        </div>

        @if ($users->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 dark:text-gray-400">Belum ada user terdaftar.</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($users as $user)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                        </div>

                        @if ($user->prodi || $user->semester)
                            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                @if ($user->prodi)
                                    <p>Prodi: {{ $user->prodi }}</p>
                                @endif
                                @if ($user->semester)
                                    <p>Semester: {{ $user->semester }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($user->offeredSkills->isNotEmpty())
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-green-700 dark:text-green-400 mb-2">Menawarkan:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->offeredSkills as $skill)
                                        <span class="inline-block px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
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
                                <h4 class="text-sm font-medium text-blue-700 dark:text-blue-400 mb-2">Mencari:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->soughtSkills as $skill)
                                        <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
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
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum menambahkan keahlian</p>
                        @endif

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Ajukan Swap</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $users->links() }}
        @endif
    </main>
</body>
</html>