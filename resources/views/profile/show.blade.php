<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profil Saya') }}
            </h2>
            <x-back-button href="{{ route('dashboard') }}" label="Dashboard" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
                <!-- Cover Image -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-32"></div>
                
                <div class="px-6 pb-6">
                    <!-- Avatar -->
                    <div class="flex flex-col items-center -mt-16 mb-4">
                        <div class="w-32 h-32 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center text-4xl font-bold text-gray-700 dark:text-gray-300 shadow-lg border-4 border-white">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <h2 class="mt-4 text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        
                        <!-- Rating & Stats -->
                        <div class="mt-4 flex flex-col items-center space-y-2">
                            @if($user->reviewsReceived->count() > 0)
                                @php
                                    $avgRating = round($user->getAverageRating(), 1);
                                    $fullStars = floor($avgRating);
                                @endphp
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $fullStars ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="text-sm font-semibold text-gray-700 ml-2">{{ $avgRating }} / 5.0</span>
                                </div>
                                <p class="text-xs text-gray-500">Dari {{ $user->reviewsReceived->count() }} review</p>
                            @else
                                <p class="text-sm text-gray-500">Belum ada rating</p>
                            @endif
                            
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    {{ $user->getTotalSwaps() }} Swaps
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    {{ $user->offeredSkills->count() + $user->soughtSkills->count() }} Skills
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Button -->
                    <div class="flex justify-center mb-6">
                        <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Edit Profil
                        </a>
                    </div>

                    <!-- Profile Information -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Akademik</h3>
                            @if ($user->prodi)
                                <div class="mb-3">
                                    <span class="text-gray-600 text-sm">Program Studi:</span>
                                    <p class="text-gray-900 font-medium">{{ $user->prodi }}</p>
                                </div>
                            @endif
                            @if ($user->semester)
                                <div class="mb-3">
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
                                <div class="mb-3">
                                    <span class="text-gray-600 text-sm">WhatsApp:</span>
                                    <p class="text-gray-900 font-medium">{{ $user->whatsapp_number }}</p>
                                </div>
                            @else
                                <p class="text-gray-500 text-sm italic">Belum diisi</p>
                            @endif
                        </div>
                    </div>

                    <!-- Skills Section -->
                    <div class="space-y-6">
                        <!-- Offered Skills -->
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

                        <!-- Sought Skills -->
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

                    <!-- Reviews Section -->
                    @if ($user->reviewsReceived->isNotEmpty())
                        <div class="mt-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Reviews yang Diterima</h3>
                            <div class="space-y-4">
                                @foreach ($user->reviewsReceived as $review)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">{{ $review->reviewer->name }}</span>
                                            <div class="flex items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if ($review->comment)
                                            <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>