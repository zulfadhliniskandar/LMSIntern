<div x-data="{ openChat: false }" class="relative">
    <!-- Floating Launcher Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <button @click="openChat = !openChat" 
                class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-full shadow-2xl hover:shadow-indigo-500/25 hover:scale-105 active:scale-95 transition-all duration-300 cursor-pointer group">
            <template x-if="!openChat">
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-indigo-600 animate-pulse"></span>
                    </div>
                    <span class="text-xs font-bold tracking-wide">Course Discussion</span>
                </div>
            </template>

            <template x-if="openChat">
                <div class="flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="text-xs font-bold">Close Chat</span>
                </div>
            </template>
        </button>
    </div>

    <!-- Floating Chat Panel Drawer -->
    <div x-show="openChat" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-6 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-6 scale-95"
         class="fixed bottom-22 right-6 z-50 w-full max-w-[380px] sm:max-w-[420px] h-[550px] bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-2xl flex flex-col overflow-hidden backdrop-blur-xl"
         style="display: none;">
        
        <!-- Chat Drawer Header -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-3.5 flex justify-between items-center border-b border-indigo-900/50">
            <div class="flex items-center gap-2.5">
                <div class="p-1.5 bg-indigo-600/30 rounded-lg">
                    <svg class="w-4 h-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm leading-tight text-slate-100">Course Discussion</h3>
                    <p class="text-[10px] text-slate-300 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-ping"></span> Live & Instant
                    </p>
                </div>
            </div>

            <button @click="openChat = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer" title="Minimize Chat">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- Pinned Announcement Banner -->
        @if($pinnedMessage)
            <div onclick="scrollToPinnedMessage({{ $pinnedMessage->id }})" 
                 class="bg-amber-50/90 dark:bg-amber-950/50 border-b border-amber-200 dark:border-amber-900/60 p-2.5 flex items-start justify-between gap-2 cursor-pointer hover:bg-amber-100/90 dark:hover:bg-amber-900/60 transition-colors shrink-0"
                 title="Click to jump to message">
                <div class="flex items-start gap-2 text-xs overflow-hidden">
                    <span class="text-xs shrink-0">📌</span>
                    <div class="truncate">
                        <span class="font-bold text-amber-900 dark:text-amber-200 text-[11px]">Pinned Announcement</span>
                        <p class="text-amber-800 dark:text-amber-300 text-xs font-medium truncate">
                            <strong class="text-amber-950 dark:text-amber-100">{{ $pinnedMessage->user->name }}:</strong> {{ $pinnedMessage->message }}
                        </p>
                    </div>
                </div>
                @if(auth()->user()?->isInstructor() || auth()->user()?->isAdmin())
                    <button wire:click.stop="togglePin({{ $pinnedMessage->id }})" class="text-[10px] font-bold text-amber-700 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-200 underline shrink-0 cursor-pointer">
                        Unpin
                    </button>
                @endif
            </div>
        @endif

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-3.5 space-y-3 bg-slate-50/50 dark:bg-zinc-900/50" id="chat-box">
            @forelse($messages as $msg)
                @php
                    $isMe = $msg->user_id === auth()->id();
                    $isLecturer = (bool) ($msg->user?->instructor || $msg->user?->admin);
                @endphp

                <div id="msg-{{ $msg->id }}" class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} transition-all">
                    <div class="flex items-center gap-1.5 mb-1 text-[11px] text-slate-500 dark:text-slate-400">
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $msg->user->name }}</span>
                        @if($isLecturer)
                            <span class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-300 px-1.5 py-0.2 rounded text-[9px] font-bold">Lecturer</span>
                        @endif
                        <span class="text-[10px] text-slate-400">{{ $msg->created_at->format('h:i A') }}</span>

                        @if(auth()->user()?->isInstructor() || auth()->user()?->isAdmin())
                            <button wire:click="togglePin({{ $msg->id }})" 
                                    class="text-slate-400 hover:text-amber-500 transition-colors ml-0.5 cursor-pointer text-[10px]" 
                                    title="{{ $msg->is_pinned ? 'Unpin Message' : 'Pin Message' }}">
                                📌
                            </button>
                        @endif
                    </div>

                    <div class="max-w-[82%] px-3.5 py-2 rounded-2xl text-xs shadow-xs transition-all leading-relaxed
                                {{ $msg->is_pinned ? 'ring-2 ring-amber-400 dark:ring-amber-500 ' : '' }}
                                {{ $isMe ? 'bg-indigo-600 text-white rounded-br-xs' : 'bg-white dark:bg-zinc-800 text-slate-800 dark:text-slate-100 border border-slate-200/80 dark:border-zinc-700/80 rounded-bl-xs' }}">
                        {{ $msg->message }}
                    </div>
                </div>
            @empty
                <div class="text-center text-slate-400 dark:text-zinc-500 py-16 text-xs">
                    No messages yet. Start the course discussion!
                </div>
            @endforelse
        </div>

        <!-- Input Bar -->
        <form wire:submit.prevent="sendMessage" class="p-2.5 bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800 flex items-center gap-2 shrink-0">
            <input type="text" wire:model="message" placeholder="Type a message..."
                class="flex-1 bg-slate-100 dark:bg-zinc-800 border-0 rounded-xl px-3.5 py-2 text-xs dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                required>
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer">
                <span>Send</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-7-9-7-9 7 9 7zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>
</div>

<!-- Auto-scroll Script -->
<script>
    function scrollToPinnedMessage(msgId) {
        const el = document.getElementById('msg-' + msgId);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const bubble = el.querySelector('.rounded-2xl');
            if (bubble) {
                bubble.classList.add('ring-4', 'ring-amber-400', 'animate-pulse');
                setTimeout(() => {
                    bubble.classList.remove('ring-4', 'ring-amber-400', 'animate-pulse');
                }, 2000);
            }
        }
    }

    document.addEventListener('livewire:initialized', () => {
        const chatBox = document.getElementById('chat-box');

        function scrollToBottom() {
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }

        scrollToBottom();

        Livewire.on('messageSent', () => {
            setTimeout(scrollToBottom, 50);
        });
    });
</script>