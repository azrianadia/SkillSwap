<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ajukan Swap Request') }}
            </h2>
            <x-back-button href="{{ route('users.show', $receiver->id) }}" label="Profil User" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Ajukan Swap ke {{ $receiver->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Pilih skill yang ingin Anda tawarkan dan skill yang Anda inginkan dari {{ $receiver->name }}</p>
                </div>

                <form method="POST" action="{{ route('swaps.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">

                    <!-- Skill yang Ditawarkan -->
                    <div>
                        <label for="offered_skill_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Skill yang Anda Tawarkan
                        </label>
                        @if ($sender->offeredSkills->isEmpty())
                            <p class="text-sm text-red-600">Anda belum menambahkan skill yang ditawarkan. <a href="{{ route('profile.edit') }}" class="underline">Tambahkan di profil</a></p>
                        @else
                            <select id="offered_skill_id" name="offered_skill_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Skill yang Anda Tawarkan --</option>
                                @foreach ($sender->offeredSkills as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->skill_name }} ({{ ucfirst($skill->pivot->proficiency_level) }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('offered_skill_id')" />
                        @endif
                    </div>

                    <!-- Skill yang Diminta -->
                    <div>
                        <label for="requested_skill_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Skill yang Anda Inginkan dari {{ $receiver->name }}
                        </label>
                        @if ($receiver->offeredSkills->isEmpty())
                            <p class="text-sm text-red-600">{{ $receiver->name }} belum menambahkan skill yang ditawarkan.</p>
                        @else
                            <select id="requested_skill_id" name="requested_skill_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Skill yang Anda Inginkan --</option>
                                @foreach ($receiver->offeredSkills as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->skill_name }} ({{ ucfirst($skill->pivot->proficiency_level) }})</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('requested_skill_id')" />
                        @endif
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900 mb-2">Informasi:</h4>
                        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                            <li>{{ $receiver->name }} akan menerima notifikasi swap request Anda</li>
                            <li>Mereka dapat menerima atau menolak request Anda</li>
                            <li>Setelah diterima, Anda bisa saling bertukar skill</li>
                        </ul>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('users.show', $receiver->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" 
                            @if ($sender->offeredSkills->isEmpty() || $receiver->offeredSkills->isEmpty()) disabled @endif>
                            Kirim Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>