<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profil {{ $user->name }}
            </h2>
            <x-back-button href="{{ route('dashboard') }}" label="Dashboard" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-32"></div>

                <div class="px-6 pb-6">
                    <div class="flex flex-col items-center -mt-16 mb-6">
                        <div class="w-32 h-32 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center text-4xl font-bold text-gray-700 dark:text-gray-300 shadow-lg border-4 border-white">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full" />
                            @else
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            @endif
                        </div>
                        <h2 class="mt-4 text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>

                        @if($user->is_pro)
                            <span class="mt-2 inline-flex items-center px-3 py-1 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-sm font-semibold rounded-full">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8-2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                PRO
                            </span>
                        @endif

                        <!-- Rating & Stats -->
                        <div class="mt-4 flex items-center space-x-6 text-sm">
                            @if($user->reviewsReceived->count() > 0)
                                @php
                                    $avgRating = round($user->getAverageRating(), 1);
                                    $fullStars = floor($avgRating);
                                @endphp
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $fullStars ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8-2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                    <span class="ml-2 font-semibold text-gray-700">{{ $avgRating }} / 5.0</span>
                                </div>
                                <span class="text-gray-500">({{ $user->reviewsReceived->count() }} review)</span>
                            @else
                                <span class="text-gray-500">Belum ada rating</span>
                            @endif

                            <div class="hidden md:flex items-center space-x-6 ml-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600">{{ $user->getTotalSwaps() }}</p>
                                    <p class="text-xs text-gray-500">Swaps</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-600">{{ $user->offeredSkills->count() + $user->soughtSkills->count() }}</p>
                                    <p class="text-xs text-gray-500">Skills</p>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Akademik</h3>
                        @if ($user->prodi)
                            <div class="mb-2">
                                <span class="text-gray-600 text-sm">Program Studi:</span>
                                <p class="text-gray-900 font-medium">{{ $user->prodi }}</p>
                            </div>
                        @endif
                        @if ($user->semester)
                            <div class="mb-2">
                                <span class="text-gray-600 text-sm">Semester:</span>
                                <p class="text-gray-900 font-medium">{{ $user->semester }}</p>
                            </div>
                        @endif
                        @if (!$user->prodi && !$user->semester)
                            <p class="text-gray-500 text-sm italic">Belum diisi</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Kontak</h3>
                        @if ($user->whatsapp_number)
                            @if ($existingSwap)
                                <div class="mb-2">
                                    <span class="text-gray-600 text-sm">WhatsApp:</span>
                                    <p class="text-gray-900 font-medium">{{ $user->whatsapp_number }}</p>
                                </div>
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Nomor WhatsApp terlihat karena swap sudah <strong>diterima/selesai</strong>.
                                </div>
                            @else
                                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Nomor WhatsApp tersembunyi. <strong>Ajukan swap</strong> dan tunggu diterima untuk melihat nomor kontak.
                                </div>
                            @endif
                        @else
                            <p class="text-gray-500 text-sm italic">Belum diisi</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            Keahlian yang Ditawarkan
                        </h3>
                        @if ($user->offeredSkills->isNotEmpty())
                            <div class="flex flex-wrap gap-3">
                                @foreach ($user->offeredSkills as $skill)
                                    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-green-800">{{ $skill->skill_name }}</p>
                                                @if ($skill->category)
                                                    <p class="text-xs text-green-600">{{ $skill->category }}</p>
                                                @endif
                                                @if ($skill->pivot->proficiency_level)
                                                    <span class="text-xs bg-green-200 text-green-800 px-2 py-0.5 rounded-full mt-1 inline-block">
                                                        {{ ucfirst($skill->pivot->proficiency_level) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">Belum menambahkan keahlian yang ditawarkan</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                            Keahlian yang Dicari
                        </h3>
                        @if ($user->soughtSkills->isNotEmpty())
                            <div class="flex flex-wrap gap-3">
                                @foreach ($user->soughtSkills as $skill)
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-blue-800">{{ $skill->skill_name }}</p>
                                                @if ($skill->category)
                                                    <p class="text-xs text-blue-600">{{ $skill->category }}</p>
                                                @endif
                                                @if ($skill->pivot->proficiency_level)
                                                    <span class="text-xs bg-blue-200 text-blue-800 px-2 py-0.5 rounded-full mt-1 inline-block">
                                                        Target: {{ ucfirst($skill->pivot->proficiency_level) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">Belum menambahkan keahlian yang dicari</p>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    @if (auth()->id() == $user->id)
                        <p class="text-center text-gray-500">Ini profil Anda</p>
                    @elseif ($pendingSwap)
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-amber-800 font-medium text-center">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Permintaan swap sedang <strong>menunggu konfirmasi</strong>.
                            </p>
                        </div>
                    @elseif ($existingSwap)
                        <div class="flex space-x-3">
                            @if ($existingSwap->status === 'completed')
                                <a href="{{ route('chat.show', $existingSwap->id) }}"
                                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200 text-center flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h4m4 0h4m-8 4h4m-4 0h4m-4-8h4m-4 0h4m0-4h4m-4 0h4"></path>
                                    </svg>
                                    Chat
                                </a>
                                @if (!$existingSwap->reviews->where('reviewer_id', auth()->id())->count())
                                    <a href="{{ route('reviews.create', $existingSwap->id) }}"
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200 text-center">
                                        Beri Review
                                    </a>
                                @endif
                            @elseif (in_array($existingSwap->status, ['accepted', 'pending']))
                                <a href="{{ route('chat.show', $existingSwap->id) }}"
                                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200 text-center flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h4m4 0h4m-8 4h4m-4 0h4m-4-8h4m-4 0h4m0-4h4m-4 0h4"></path>
                                    </svg>
                                    Chat
                                </a>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('swaps.create', $user->id) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200 text-center">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h4m4 0h4m-8 4h4m-4 0h4m-4-8h4m-4 0h4m0-4h4m-4 0h4"></path>
                            </svg>
                            Ajukan Swap
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>