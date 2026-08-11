<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" @if($hasProcessing) wire:poll.5s="loadAudioList" @endif>
    
    {{-- Top Hero Header --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-xl">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <svg class="size-96 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
            </svg>
        </div>

        <div class="relative z-10 space-y-4 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-md text-blue-200 border border-white/10">
                <span class="inline-block size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                NotebookLM Integration
            </div>
            
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                NotebookLM Audio Overview Podcast Generator
            </h1>

            <p class="text-sm sm:text-base text-blue-100/90 leading-relaxed font-sans">
                Transform study materials, lecture slides, and notes into Google NotebookLM's famous 2-host conversational "Deep Dive" podcast discussions with realistic human dialogue, pauses, and insights.
            </p>

            {{-- Authentication Status Badge & Action --}}
            <div class="pt-2 flex flex-wrap items-center gap-3">
                @if($isAuthSetup)
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-xs font-semibold">
                        <svg class="size-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Google Session Authenticated
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-amber-500/20 border border-amber-500/40 text-amber-300 text-xs font-semibold">
                        <svg class="size-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        NotebookLM Session Setup Recommended
                    </div>
                @endif

                <button wire:click="$set('showAuthModal', true)"
                    class="px-3.5 py-1.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-xs font-semibold text-white transition-all cursor-pointer">
                    ⚙️ Session Credentials
                </button>
            </div>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-sm font-semibold text-emerald-800 dark:text-emerald-200 flex items-center gap-3">
            <svg class="size-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 rounded-2xl text-sm font-semibold text-rose-800 dark:text-rose-200 flex items-center gap-3">
            <svg class="size-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('auth_message'))
        <div class="p-4 bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 rounded-2xl text-sm font-semibold text-blue-800 dark:text-blue-200 flex items-center gap-3">
            <svg class="size-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('auth_message') }}
        </div>
    @endif

    {{-- Main Generator Section --}}
    <div class="bg-white dark:bg-neutral-900 rounded-3xl shadow-sm border border-neutral-200 dark:border-neutral-800 p-6 sm:p-8 space-y-6">
        <h2 class="text-xl font-bold text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
            <span>🎙️</span> Create NotebookLM Deep Dive Podcast
        </h2>

        {{-- Source Mode Selector Tabs --}}
        <div class="flex border-b border-neutral-200 dark:border-neutral-800 gap-6 text-sm font-semibold">
            <button type="button" wire:click="$set('sourceMode', 'text')"
                class="pb-3 transition-all relative cursor-pointer {{ $sourceMode === 'text' ? 'text-indigo-600 dark:text-indigo-400 font-bold border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-neutral-500 hover:text-neutral-700 dark:text-neutral-400' }}">
                ✍️ Paste Study Text
            </button>
            <button type="button" wire:click="$set('sourceMode', 'file')"
                class="pb-3 transition-all relative cursor-pointer {{ $sourceMode === 'file' ? 'text-indigo-600 dark:text-indigo-400 font-bold border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-neutral-500 hover:text-neutral-700 dark:text-neutral-400' }}">
                📄 Upload Document
            </button>
            <button type="button" wire:click="$set('sourceMode', 'material')"
                class="pb-3 transition-all relative cursor-pointer {{ $sourceMode === 'material' ? 'text-indigo-600 dark:text-indigo-400 font-bold border-b-2 border-indigo-600 dark:border-indigo-400' : 'text-neutral-500 hover:text-neutral-700 dark:text-neutral-400' }}">
                📚 Select from My Library
            </button>
        </div>

        {{-- Form Controls --}}
        <form wire:submit.prevent="generate" class="space-y-6">
            {{-- Podcast Title --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                    Podcast Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" wire:model="title" placeholder="e.g. Operating Systems Chapter 1 Overview"
                    class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                @error('title') <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Mode 1: Text Input --}}
            @if($sourceMode === 'text')
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Source Material Text <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-xs text-neutral-400 dark:text-neutral-500 font-mono">
                            {{ number_format(strlen($textInput)) }} / 50,000 chars
                        </span>
                    </div>
                    <textarea wire:model="textInput" rows="7" placeholder="Paste study notes, lecture content, or topic summary..."
                        class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-sans leading-relaxed"></textarea>
                    @error('textInput') <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- Mode 2: File Upload --}}
            @if($sourceMode === 'file')
                <div class="space-y-3" x-data="{ isDragging: false, fileName: '', fileSize: '' }">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Upload Document (PDF, DOCX, TXT, PPTX) <span class="text-rose-500">*</span>
                    </label>

                    <div class="relative border-2 border-dashed rounded-2xl p-8 text-center transition-all cursor-pointer"
                        :class="isDragging ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/30' : 'border-neutral-200 dark:border-neutral-800 hover:border-indigo-400 bg-neutral-50/50 dark:bg-neutral-950/40'"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="isDragging = false">

                        <input type="file" wire:model="file" accept=".pdf,.docx,.txt,.pptx"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            @change="
                                if ($event.target.files.length) {
                                    fileName = $event.target.files[0].name;
                                    fileSize = ($event.target.files[0].size / (1024*1024)).toFixed(2) + ' MB';
                                }
                            ">

                        <div class="space-y-2 pointer-events-none">
                            <div class="size-12 mx-auto rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200">
                                Click or drag document file here
                            </p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Supports PDF, DOCX, TXT, PPTX (Max 20MB)</p>
                        </div>
                    </div>

                    @error('file') <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- Mode 3: Existing Learning Material --}}
            @if($sourceMode === 'material')
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Select Learning Material <span class="text-rose-500">*</span>
                    </label>
                    <select wire:model.live="selectedMaterialId"
                        class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <option value="">-- Choose a material from library --</option>
                        @foreach($materials as $mat)
                            <option value="{{ $mat->id }}">{{ $mat->title }} ({{ strtoupper($mat->file_type ?? 'FILE') }})</option>
                        @endforeach
                    </select>
                    @error('selectedMaterialId') <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- Submit Action Button --}}
            <div class="pt-2">
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                    <span wire:loading.remove wire:target="generate" class="flex items-center gap-2">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                        </svg>
                        Generate NotebookLM Audio Overview
                    </span>
                    <span wire:loading wire:target="generate" class="flex items-center gap-2">
                        <svg class="animate-spin size-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Starting Generation Request...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Audio Library Section --}}
    <div class="bg-white dark:bg-neutral-900 rounded-3xl shadow-sm border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <div class="p-6 border-b border-neutral-100 dark:border-neutral-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-xl">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">NotebookLM Audio Library</h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ count($audioList) }} Audio Overview{{ count($audioList) !== 1 ? 's' : '' }}</p>
                </div>
            </div>

            @if($hasProcessing)
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-full text-xs font-semibold">
                    <svg class="animate-spin size-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Generating in background...
                </span>
            @endif
        </div>

        @if(count($audioList) > 0)
            <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @foreach($audioList as $audio)
                    <div class="p-5 sm:p-6 hover:bg-neutral-50/50 dark:hover:bg-neutral-800/30 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                {{-- Status icon --}}
                                <div class="shrink-0 mt-0.5">
                                    @if($audio['status'] === 'completed')
                                        <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl">
                                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    @elseif($audio['status'] === 'processing' || $audio['status'] === 'pending')
                                        <div class="p-2.5 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-xl">
                                            <svg class="animate-spin size-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </div>
                                    @else
                                        <div class="p-2.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl">
                                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Title & details --}}
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-bold text-neutral-900 dark:text-neutral-100 truncate">{{ $audio['title'] }}</h3>
                                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-full border border-blue-200/60 dark:border-blue-800/60">
                                            NotebookLM Deep Dive
                                        </span>
                                        @if($audio['fileSize'])
                                            <span class="text-[11px] text-neutral-400 dark:text-neutral-500">📁 {{ $audio['fileSize'] }}</span>
                                        @endif
                                        <span class="text-[11px] text-neutral-400 dark:text-neutral-500">{{ $audio['createdAt'] }} · {{ $audio['createdAtTime'] }}</span>
                                    </div>

                                    @if($audio['status'] === 'failed' && $audio['error'])
                                        <p class="text-xs text-rose-500 dark:text-rose-400 mt-1.5 bg-rose-50 dark:bg-rose-950/40 px-3 py-1.5 rounded-lg">
                                            ⚠️ {{ $audio['error'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 shrink-0">
                                @if($audio['status'] === 'completed')
                                    <button wire:click="playAudio({{ $audio['id'] }})"
                                        class="p-2.5 rounded-xl {{ $currentPlaying === $audio['id'] ? 'bg-blue-600 text-white' : 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 hover:bg-blue-100' }} transition-colors cursor-pointer"
                                        title="Play Audio">
                                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </button>

                                    @if($audio['downloadUrl'])
                                        <a href="{{ $audio['downloadUrl'] }}" download
                                            class="p-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200 dark:hover:bg-neutral-700 rounded-xl transition-colors">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>
                                    @endif
                                @endif

                                <button wire:click="deleteAudio({{ $audio['id'] }})" wire:confirm="Delete this NotebookLM Audio Overview?"
                                    class="p-2.5 text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors cursor-pointer">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Inline Audio Player --}}
                        @if($currentPlaying === $audio['id'] && $audio['audioUrl'])
                            <div class="mt-4 p-4 bg-blue-50/50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 rounded-2xl">
                                <audio controls autoplay class="w-full">
                                    <source src="{{ $audio['audioUrl'] }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-neutral-400 dark:text-neutral-500 space-y-2">
                <svg class="size-12 mx-auto text-neutral-300 dark:text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                <p class="font-medium text-sm">No NotebookLM Audio Overviews generated yet.</p>
            </div>
        @endif
    </div>

    {{-- Session Setup Modal --}}
    @if($showAuthModal)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-neutral-900 rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-neutral-200 dark:border-neutral-800">
                <div class="flex justify-between items-center border-b border-neutral-100 dark:border-neutral-800 pb-4">
                    <h3 class="text-lg font-bold text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
                        ⚙️ NotebookLM Credentials Setup
                    </h3>
                    <button wire:click="$set('showAuthModal', false)" class="text-neutral-400 hover:text-neutral-600 text-sm font-bold cursor-pointer">✕</button>
                </div>

                <div class="text-xs text-neutral-600 dark:text-neutral-400 space-y-2 leading-relaxed">
                    <p>To use Google NotebookLM programmatically, run the following CLI command once in your terminal to authenticate your Google Account:</p>
                    <div class="p-3 bg-neutral-900 text-emerald-400 font-mono rounded-xl text-xs flex justify-between items-center">
                        <code>python -m notebooklm login</code>
                    </div>
                    <p>Or paste your <code>storage_state.json</code> JSON contents below to save custom credentials locally:</p>
                </div>

                <div class="space-y-2">
                    <textarea wire:model="storageStateJson" rows="6" placeholder='{"cookies": [...], "origins": [...]}'
                        class="w-full p-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-xs font-mono text-neutral-800 dark:text-neutral-200"></textarea>
                    @error('storageStateJson') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="$set('showAuthModal', false)" class="px-4 py-2 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 font-bold rounded-xl text-xs cursor-pointer">Cancel</button>
                    <button wire:click="saveAuthStorageState" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-all cursor-pointer">Save Credentials</button>
                </div>
            </div>
        </div>
    @endif
</div>
