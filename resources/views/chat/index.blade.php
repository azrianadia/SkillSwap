<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pesan') }}
            </h2>
            <x-back-button href="{{ route('dashboard') }}" label="Dashboard" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                @if ($swaps->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h4m4 0h4m-8 4h4m-4 0h4m-4-8h4m-4 0h4m0-4h4m-4 0h4" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada percakapan</h3>
                        <p class="mt-2 text-gray-500">Mulai swap dengan user lain untuk memulai chat</p>
                        <a href="{{ route('dashboard') }}" class="mt-6 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Cari Partner Swap
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($swaps as $swap)
                            @php
                                $otherUser = $swap->sender_id === auth()->id() ? $swap->receiver : $swap->sender;
                                $lastMessage = $swap->messages()->latest()->first();
                                $unreadCount = $swap->messages()
                                    ->where('receiver_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <a href="{{ route('chat.show', $swap->id) }}" 
                               class="block p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <!-- Avatar -->
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($otherUser->name, 0, 2)) }}
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h4 class="font-semibold text-gray-900 truncate">{{ $otherUser->name }}</h4>
                                            @if ($lastMessage)
                                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                                    {{ $lastMessage->created_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between mt-1">
                                            <p class="text-sm text-gray-500 truncate flex-1">
                                                @if ($lastMessage)
                                                    {{ $lastMessage->sender_id === auth()->id() ? 'Anda: ' : '' }}
                                                    {{ Str::limit($lastMessage->content, 50) }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum ada pesan</span>
                                                @endif
                                            </p>
                                            @if ($unreadCount > 0)
                                                <span class="ml-2 px-2 py-0.5 bg-indigo-600 text-white text-xs rounded-full">
                                                    {{ $unreadCount }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Swap Info -->
                                    <div class="flex flex-col items-end space-y-1">
                                        <span class="text-xs px-2 py-1 bg-green-50 text-green-700 rounded-full">
                                            {{ $swap->offeredSkill->skill_name }} ↔ {{ $swap->requestedSkill->skill_name }}
                                        </span>
                                        <span class="text-xs px-2 py-1 bg-gray-50 text-gray-600 rounded-full">
                                            {{ ucfirst($swap->status) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>