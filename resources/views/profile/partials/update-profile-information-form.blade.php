<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar Upload -->
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                @if ($user->avatar)
                    <img id='preview' class="h-16 w-16 object-cover rounded-full" src="{{ asset('storage/' . $user->avatar) }}" alt="Current profile photo" />
                @else
                    <div id='preview-placeholder' class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-4" x-data="{ showConfirm: false }">
                <label class="block">
                    <span class="sr-only">Choose profile photo</span>
                    <input type="file" name="avatar" id="avatar" class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100
                    " onchange="previewImage(event)"/>
                </label>

                @if ($user->avatar)
                    <button type="button" 
                            class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-full font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:text-red-500 focus:outline-none focus:border-red-300 focus:ring focus:ring-red-200 active:text-red-800 active:bg-gray-50 disabled:opacity-25 transition"
                            @click="showConfirm = true"
                            id="delete-btn">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Foto
                    </button>

                    <!-- Confirmation Modal -->
                    <div x-show="showConfirm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showConfirm = false"></div>
                            
                            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                            <h3 class="text-base font-semibold leading-6 text-gray-900">Konfirmasi Hapus</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus foto profil? Foto akan dikembalikan ke inisial nama.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                    <button type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" @click="showConfirm = false; deleteAvatar()">Hapus</button>
                                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="showConfirm = false">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Hidden input for delete_avatar flag -->
        <input type="hidden" name="delete_avatar" id="delete_avatar" value="0">
        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />

        <script>
            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    let preview = document.getElementById('preview');
                    const placeholder = document.getElementById('preview-placeholder');
                    
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'preview';
                        preview.className = 'h-16 w-16 object-cover rounded-full';
                        placeholder.parentNode.replaceChild(preview, placeholder);
                    }
                    preview.src = reader.result;
                    
                    // Reset delete_avatar flag if a new file is chosen
                    document.getElementById('delete_avatar').value = '0';
                    const deleteBtn = document.getElementById('delete-btn');
                    if (deleteBtn) {
                        deleteBtn.style.display = 'inline-flex';
                    }
                }
                reader.readAsDataURL(event.target.files[0]);
            }

            function deleteAvatar() {
                // Set hidden input to trigger deletion on form submit
                document.getElementById('delete_avatar').value = '1';
                
                // Update UI immediately to show placeholder
                const preview = document.getElementById('preview');
                const placeholder = document.getElementById('preview-placeholder');
                const deleteBtn = document.getElementById('delete-btn');
                
                if (preview && placeholder) {
                    // Create new placeholder
                    const newPlaceholder = document.createElement('div');
                    newPlaceholder.id = 'preview-placeholder';
                    newPlaceholder.className = 'h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold';
                    newPlaceholder.textContent = '{{ strtoupper(substr($user->name, 0, 1)) }}';
                    
                    preview.parentNode.replaceChild(newPlaceholder, preview);
                }
                
                // Hide delete button
                if (deleteBtn) {
                    deleteBtn.style.display = 'none';
                }
                
                // Clear file input
                const fileInput = document.getElementById('avatar');
                if (fileInput) {
                    fileInput.value = '';
                }
            }
        </script>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="prodi" :value="__('Program Studi')" />
            <x-text-input id="prodi" name="prodi" type="text" class="mt-1 block w-full" :value="old('prodi', $user->prodi)" />
            <x-input-error class="mt-2" :messages="$errors->get('prodi')" />
        </div>

        <div>
            <x-input-label for="semester" :value="__('Semester')" />
            <x-text-input id="semester" name="semester" type="number" class="mt-1 block w-full" :value="old('semester', $user->semester)" min="1" max="14" />
            <x-input-error class="mt-2" :messages="$errors->get('semester')" />
        </div>

        <div>
            <x-input-label for="whatsapp_number" :value="__('Nomor WhatsApp')" />
            <x-text-input id="whatsapp_number" name="whatsapp_number" type="text" class="mt-1 block w-full" :value="old('whatsapp_number', $user->whatsapp_number)" />
            <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
