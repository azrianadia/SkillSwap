<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifikasi') }}
            </h2>
            <x-back-button href="{{ route('dashboard') }}" label="Dashboard" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Semua Notifikasi</h3>
                        @if($notifications->total() > 0)
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                    Tandai Semua Telah Dibaca
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($notifications->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.001 6.001 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">Tidak ada notifikasi</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($notifications as $notification)
                                <a href="{{ route('notifications.read', $notification->id) }}" 
                                   class="block p-4 rounded-lg transition {{ $notification->read_at ? 'bg-white hover:bg-gray-50' : 'bg-blue-50 hover:bg-blue-100' }}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            @if($notification->read_at)
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.001 6.001 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.001 6.001 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm {{ $notification->read_at ? 'text-gray-700' : 'text-gray-900 font-medium' }}">
                                                {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if(!$notification->read_at)
                                            <div class="ml-3">
                                                <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>