<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Beri Review') }}
            </h2>
            <x-back-button href="{{ route('swaps.index') }}" label="My Swaps" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Review untuk {{ $reviewee->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Bagaimana pengalaman Anda bertukar skill dengan {{ $reviewee->name }}?</p>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Detail Swap:</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Skill Ditawarkan:</span> {{ $swap->offeredSkill->skill_name }}
                        </div>
                        <div>
                            <span class="font-medium">Skill Diterima:</span> {{ $swap->requestedSkill->skill_name }}
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('reviews.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="swap_id" value="{{ $swap->id }}">
                    <input type="hidden" name="reviewee_id" value="{{ $reviewee->id }}">

                    <!-- Rating -->
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">
                            Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" required>
                                    <svg class="w-8 h-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </label>
                            @endfor
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                    </div>

                    <!-- Comment -->
                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            Komentar (Opsional)
                        </label>
                        <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Tuliskan pengalaman Anda...">{{ old('comment') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                        <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('swaps.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>