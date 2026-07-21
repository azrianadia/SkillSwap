<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Status Langganan Pro</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Account Info Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white rounded-t-lg">
                    <h3 class="text-lg font-bold">Informasi Akun</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-blue-700">Paket</p>
                            <p class="font-medium text-blue-900">Pro</p>
                        </div>
                        <div>
                            <p class="text-blue-700">Status</p>
                            <p class="font-medium text-blue-900">{{ $user->isSubscriptionActive() ? 'Aktif' : 'Tidak Aktif' }}</p>
                        </div>
                        <div>
                            <p class="text-blue-700">Swap Limit</p>
                            <p class="font-medium text-blue-900">Unlimited</p>
                        </div>
                        <div>
                            <p class="text-blue-700">Sisa Swap</p>
                            <p class="font-medium text-blue-900">Unlimited</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-blue-700">Reset Otomatis</p>
                            <p class="font-medium text-blue-900">{{ $quota['formatted_reset'] ?? 'Tidak tersedia' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pro Features -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Fitur Pro</h4>
                <ul class="space-y-3 text-sm text-gray-700">
                    @foreach(config('subscription.plans.pro.features') as $feature)
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Recent Transactions -->
            @if($transactions->isNotEmpty())
                <div class="bg-white shadow rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-2">Transaksi Terakhir</h4>
                    <table class="min-w-full text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-2 py-1 text-xs font-medium text-gray-600">Order ID</th>
                                <th class="px-2 py-1 text-xs font-medium text-gray-600">Jumlah</th>
                                <th class="px-2 py-1 text-xs font-medium text-gray-600">Status</th>
                                <th class="px-2 py-1 text-xs font-medium text-gray-600">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                <tr class="border-t">
                                    <td class="px-2 py-1 text-sm">{{ $tx->order_id }}</td>
                                    <td class="px-2 py-1 text-sm">Rp {{ number_format($tx->amount,0,',','.') }}</td>
                                    <td class="px-2 py-1 text-sm capitalize">{{ $tx->status }}</td>
                                    <td class="px-2 py-1 text-sm">{{ $tx->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Tidak ada transaksi.</p>
            @endif

            <div class="mt-6">
                <a href="{{ route('upgrade.show') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Kelola Langganan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
