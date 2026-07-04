<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $otherUser->name }}
            </h2>
            <x-back-button href="{{ route('chat.index') }}" label="Pesan" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden flex h-[calc(100vh-200px)] min-h-[500px]">
                <!-- Sidebar - Swap Info (Mobile: Bottom Sheet) -->
                <div class="hidden lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-gray-100">
                    <div class="p-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Detail Swap</h3>
                    </div>
                    <div class="p-4 space-y-4 overflow-y-auto flex-1">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Status</p>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                @if ($swap->status === 'accepted')
                                    bg-green-100 text-green-800
                                @elseif ($swap->status === 'completed')
                                    bg-emerald-100 text-emerald-800
                                @elseif ($swap->status === 'rejected')
                                    bg-red-100 text-red-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif
                            ">
                                {{ ucfirst($swap->status) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Skill Ditawarkan</p>
                            <span class="inline-block px-2 py-1 bg-green-50 text-green-700 rounded-full text-sm">
                                {{ $swap->offeredSkill->skill_name }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Skill Diminta</p>
                            <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                                {{ $swap->requestedSkill->skill_name }}
                            </span>
                        </div>

                        @if ($swap->status === 'completed')
                            <div class="pt-4 border-t border-gray-100">
                                <a href="{{ route('reviews.create', $swap->id) }}" 
                                   class="block w-full text-center py-2 px-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                                    Beri Review
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="flex-1 flex flex-col">
                    <!-- Chat Header (Mobile) -->
                    <div class="lg:hidden p-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($otherUser->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $otherUser->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $swap->offeredSkill->skill_name }} ↔ {{ $swap->requestedSkill->skill_name }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($swap->status === 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mx-4 mb-4">
                            <p class="text-sm text-yellow-800 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Swap ini masih <strong>Pending</strong>. Setelah diterima, nomor WhatsApp akan terlihat di profil.
                            </p>
                        </div>
                    @endif

                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-6" id="messagesContainer">
                        @foreach ($messages as $message)
                            @php
                                $isOwn = $message->sender_id === auth()->id();
                            @endphp
                            <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md {{ $isOwn ? 'flex flex-col items-end' : 'flex flex-col items-start' }}">
                                    @if (!$isOwn)
                                        <p class="text-xs text-gray-500 mb-1 ml-1">{{ $message->sender->name }}</p>
                                    @endif
                                    <div class="relative {{ $isOwn ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-sm' : 'bg-gray-100 text-gray-900 rounded-2xl rounded-tl-sm' }} pr-8">
                                        <p class="py-2 px-4">{{ $message->content }}</p>
                <span class="absolute bottom-2 {{ $isOwn ? 'right-2' : 'left-2' }} text-[10px] {{ $isOwn ? 'text-gray-400' : 'text-gray-400' }}">
                    {{ $message->created_at->format('H:i') }}
                </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($messages->isEmpty())
                            <div class="text-center py-12 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h4m4 0h4m-8 4h4m-4 0h4m-4-8h4m-4 0h4m0-4h4m-4 0h4" />
                                </svg>
                                <p class="mt-4">Belum ada pesan. Mulai percakapan!</p>
                            </div>
                        @endif
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        <form id="messageForm" class="flex items-center space-x-3">
                            @csrf
                            <input type="hidden" name="swap_id" value="{{ $swap->id }}">
                            <div class="flex-1 relative">
                                <input type="text"
                                       name="content"
                                       id="messageInput"
                                       placeholder="Ketik pesan..."
                                       class="w-full px-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent pr-12"
                                       autocomplete="off">
                            </div>
                            <button type="submit"
                                    class="p-3 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors disabled:opacity-50"
                                    id="sendBtn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
console.log('Chat script loaded');
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('messageForm');
    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const container = document.getElementById('messagesContainer');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Form submitted');
        
        const content = input.value.trim();
        if (!content) return;

        sendBtn.disabled = true;
        sendBtn.innerHTML = '<svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        try {
            const formData = new FormData(form);
            // Add _token to FormData explicitly
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
            console.log('Sending message:', content);
            const response = await fetch('{{ route("chat.store") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            });

            console.log('Response status:', response.status);
            const responseText = await response.text();
            console.log('Response text:', responseText);
            
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('JSON parse error:', e, responseText);
                throw new Error('Invalid JSON response: ' + responseText.substring(0, 200));
            }

            console.log('Parsed data:', data);

            if (data.success) {
                input.value = '';
                
                // Append message to container
                appendMessage(data.message, true);
                // Update lastMessageId to avoid duplicate from polling
                lastMessageId = data.message.id;
                
                // Scroll to bottom
                container.scrollTop = container.scrollHeight;
            } else {
                console.error('Server returned error:', data);
                alert('Gagal mengirim pesan: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal mengirim pesan: ' + error.message);
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>`;
        }
    });

function appendMessage(message, isOwn) {
    const div = document.createElement('div');
    div.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
    
    const time = new Date(message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    
    div.innerHTML = `
        <div class="flex ${isOwn ? 'flex-col items-end' : 'flex-col items-start'} max-w-xs lg:max-w-md">
            ${!isOwn ? `<p class="text-xs text-gray-500 mb-1 ml-1">${message.sender.name}</p>` : ''}
            <div class="relative ${isOwn ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-sm' : 'bg-gray-100 text-gray-900 rounded-2xl rounded-tl-sm'} pr-8">
                <p class="py-2 px-4">${escapeHtml(message.content)}</p>
                <span class="absolute bottom-2 ${isOwn ? 'right-2' : 'left-2'} text-[10px] ${isOwn ? 'text-indigo-100' : 'text-gray-400'}">${time}</span>
            </div>
        </div>
    `;
    
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Auto scroll to bottom on load
    container.scrollTop = container.scrollHeight;
    
    // ===== POLLING FOR NEW MESSAGES =====
    let lastMessageId = {{ $messages->last()->id ?? 0 }};
    let isPolling = true;
    
    function pollMessages() {
        if (!isPolling) return;
        
        fetch(`{{ route('chat.poll', $swap->id) }}?after=${lastMessageId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                // Only process messages newer than lastMessageId to avoid duplicates
                const newMsgs = data.messages.filter(msg => msg.id > lastMessageId);
                newMsgs.forEach(msg => {
                    const isOwn = msg.sender_id === {{ auth()->id() }};
                    appendMessage(msg, isOwn);
                    lastMessageId = msg.id;
                });
                container.scrollTop = container.scrollHeight;
            }
        })
        .catch(err => console.error('Polling error:', err))
        .finally(() => {
            if (isPolling) {
                setTimeout(pollMessages, 3000); // Poll every 3 seconds
            }
        });
    }
    
    // Start polling
    pollMessages();
    
    // Stop polling when page unloads
    window.addEventListener('beforeunload', () => {
        isPolling = false;
    });

    // Enter to send, Shift+Enter for new line
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
});
</script>
@endpush

</x-app-layout>