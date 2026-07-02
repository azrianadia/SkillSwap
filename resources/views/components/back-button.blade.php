@props(['href', 'label', 'class' => ''])

<a href="{{ $href }}"
   class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 {{ $class }}">
    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    {{ $label }}
</a>