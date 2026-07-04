<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Swap Requests') }}
            </h2>
            <x-back-button href="{{ route('dashboard') }}" label="Dashboard" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Sent Swaps -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Swap yang Dikirim</h3>
                    <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline">Ajukan Swap Baru</a>
                </div>

                @if ($sentSwaps->isEmpty())
                    <p class="text-gray-500 text-center py-8">Belum ada swap request yang dikirim</p>
                @else
                    <div class="space-y-4">
                        @foreach ($sentSwaps as $swap)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900"><a href="{{ route('users.show', $swap->receiver->id) }}" class="text-blue-600 hover:underline">{{ $swap->receiver->name }}</a></h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if ($swap->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif ($swap->status === 'accepted')
                                            bg-green-100 text-green-800
                                        @elseif ($swap->status === 'completed')
                                            bg-emerald-100 text-emerald-800
                                        @elseif ($swap->status === 'rejected')
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($swap->status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span class="font-medium">Ditawarkan:</span> {{ $swap->offeredSkill->skill_name }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Diminta:</span> {{ $swap->requestedSkill->skill_name }}
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if ($swap->status === 'pending')
                                        <form method="POST" action="{{ route('swaps.reject', $swap->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin membatalkan request ini?')">Batalkan</button>
                                        </form>
                                    @elseif ($swap->status === 'accepted')
                                        @if (! $swap->reviews->where('reviewer_id', Auth::id())->count())
                                            <a href="{{ route('reviews.create', $swap->id) }}" class="text-xs text-blue-600 hover:text-blue-800">Beri Review</a>
                                        @endif
                                        <form method="POST" action="{{ route('swaps.complete', $swap->id) }}" class="inline ml-2">
                                            @csrf
                                            <button type="submit" class="text-xs text-emerald-600 hover:text-emerald-800" onclick="return confirm('Yakin ingin menandai swap ini selesai?')">Selesai</button>
                                        </form>
                                        <a href="{{ route('chat.show', $swap->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 ml-2">Chat</a>
                                    @elseif ($swap->status === 'completed')
                                        <span class="text-xs text-emerald-600">✓ Selesai</span>
                                        @if (! $swap->reviews->where('reviewer_id', Auth::id())->count())
                                            <a href="{{ route('reviews.create', $swap->id) }}" class="text-xs text-blue-600 hover:text-blue-800 ml-2">Beri Review</a>
                                        @endif
                                        <a href="{{ route('chat.show', $swap->id) }}" class="text-xs text-indigo-600 hover:text-indigo-800 ml-2">Chat</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Received Swaps -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Swap yang Diterima</h3>

                @if ($receivedSwaps->isEmpty())
                    <p class="text-gray-500 text-center py-8">Belum ada swap request yang diterima</p>
                @else
                    <div class="space-y-4">
                        @foreach ($receivedSwaps as $swap)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900"><a href="{{ route('users.show', $swap->sender->id) }}" class="text-blue-600 hover:underline">{{ $swap->sender->name }}</a></h4>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if ($swap->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif ($swap->status === 'accepted')
                                            bg-green-100 text-green-800
                                        @elseif ($swap->status === 'completed')
                                            bg-emerald-100 text-emerald-800
                                        @elseif ($swap->status === 'rejected')
                                            bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($swap->status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span class="font-medium">Ditawarkan:</span> {{ $swap->offeredSkill->skill_name }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Diminta:</span> {{ $swap->requestedSkill->skill_name }}
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if ($swap->status === 'pending')
                                        <form method="POST" action="{{ route('swaps.accept', $swap->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">Terima</button>
                                        </form>
                                        <form method="POST" action="{{ route('swaps.reject', $swap->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">Tolak</button>
                                        </form>
                                    @elseif ($swap->status === 'accepted')
                                        @if (! $swap->reviews->where('reviewer_id', Auth::id())->count())
                                            <a href="{{ route('reviews.create', $swap->id) }}" class="text-xs text-blue-600 hover:text-blue-800">Beri Review</a>
                                        @endif
                                        <form method="POST" action="{{ route('swaps.complete', $swap->id) }}" class="inline ml-2">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">Selesai</button>
                                        </form>
                                    @elseif ($swap->status === 'completed')
                                        <span class="text-xs text-emerald-600">✓ Selesai</span>
                                        @if (! $swap->reviews->where('reviewer_id', Auth::id())->count())
                                            <a href="{{ route('reviews.create', $swap->id) }}" class="text-xs text-blue-600 hover:text-blue-800 ml-2">Beri Review</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>