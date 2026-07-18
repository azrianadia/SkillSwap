<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Upgrade Berhasil</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-lg shadow p-8">
                @if ($transaction && $transaction->status === 'settlement')
                    <div class="w-20 h-20 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Upgrade Berhasil!</h3>
                    <p class="text-gray-500 mb-6">Akun Anda sekarang telah menjadi <strong>Pro</strong>. Nikmati swap unlimited!</p>
                    
                    <div class="p-4 bg-green-50 rounded-lg mb-6 text-left">
                        <p class="text-sm text-green-800"><strong>Order ID:</strong> {{ $transaction->order_id }}</p>
                        <p class="text-sm text-green-800"><strong>Jumlah:</strong> Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                        <p class="text-sm text-green-800"><strong>Status:</strong> <span class="font-semibold">Lunas</span></p>
                    </div>
                @else
                    <div class="w-20 h-20 mx-auto mb-6 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Diproses</h3>
                    <p class="text-gray-500 mb-6">Pembayaran Anda sedang diproses. Status akan diperbarui otomatis.</p>
                @endif

                <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>