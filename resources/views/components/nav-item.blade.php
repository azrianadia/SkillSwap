@props(['href', 'icon', 'badge' => null])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors']) }}>
    @if ($icon === 'dashboard')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
    @elseif ($icon === 'swap')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h7V2l5 3-5 3V6H4V4zM20 20h-7v2l-5-3 5-3v2h7v-2z" />
        </svg>
    @elseif ($icon === 'chat')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h4m4 0h4m-8 4h4m-4 0h4m-4-8h4m-4 0h4m0-4h4m-4 0h4" />
        </svg>
    @elseif ($icon === 'notifications')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.001 6.001 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
    @elseif ($icon === 'profile')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    @elseif ($icon === 'logout')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
    @endif
    <span>{{ $slot }}</span>
    @if ($badge !== null)
        <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium leading-none text-white transform transition-transform duration-200 bg-red-600 rounded-full">
            {{ $badge }}
        </span>
    @endif
</a>