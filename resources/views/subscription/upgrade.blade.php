<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Upgrade ke Pro
            </h2>
            <x-back-button href="{{ route('dashboard') }}" label="Dashboard" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Current Status Card -->
            <div class="mb-8 p-6 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Saat Ini</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-500">Paket</p>
                        <p class="text-2xl font-bold text-blue-600">{{ ucfirst($quota['plan']) }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-500">Sisa Swap Bulan Ini</p>
                        <p class="text-3xl font-bold text-green-600">{{ $quota['remaining'] }}</p>
                    </div>
                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-gray-500">Reset Otomatis</p>
                        <p class="text-lg font-semibold text-yellow-600">{{ $quota['formatted_reset'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Upgrade Card -->
            <div class="bg-white rounded-lg shadow p-8">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Upgrade ke Pro</h3>
                    <p class="text-gray-500 mt-2">Swap unlimited, badge Pro, dan fitur premium lainnya</p>
                </div>

                <div class="max-w-md mx-auto">
                    <div class="p-6 bg-gray-50 rounded-lg mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xl font-bold text-gray-900">Rp 25.000</span>
                            <span class="text-green-600 font-semibold">/ bulan</span>
                        </div>
                        <ul class="space-y-3 text-sm text-gray-600">
                            @foreach(config('subscription.plans.pro.features') as $feature)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
                    @endif

                    <a href="{{ route('upgrade.confirm') }}" 
                       class="w-full py-3 px-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition text-center block">
                        <span>Aktifkan Pro - Rp 25.000/bulan</span>
                    </a>

                    <p class="mt-4 text-xs text-gray-500 text-center">
                        Pembayaran aman via Midtrans (QRIS, Transfer Bank, E-Wallet).
                        Dapat dibatalkan kapan saja.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>