<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto space-y-8 w-full overflow-hidden"
    x-data="{
        showNotification: false,
        notificationMessage: '',
        notificationType: 'success',
        fileName: '',
        fileSize: '',
        notify(message, type = 'success') {
            this.notificationMessage = message;
            this.notificationType = type;
            this.showNotification = true;
            setTimeout(() => this.showNotification = false, 4000);
        }
    }"
    x-on:notify.window="notify($event.detail.message, $event.detail.type)">

    {{-- Floating Notification Toast --}}
    <div x-show="showNotification" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed top-6 right-6 z-50 max-w-sm w-full"
    >
        <div :class="{
            'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300': notificationType === 'success',
            'bg-rose-50 dark:bg-rose-950/60 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300': notificationType === 'error',
            'bg-indigo-50 dark:bg-indigo-950/60 border-indigo-200 dark:border-indigo-800 text-indigo-800 dark:text-indigo-300': notificationType === 'info'
        }" class="p-4 rounded-2xl border shadow-lg flex items-center gap-3">
            <template x-if="notificationType === 'success'">
                <svg class="size-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <template x-if="notificationType === 'error'">
                <svg class="size-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <span class="text-sm font-semibold" x-text="notificationMessage"></span>
            <button @click="showNotification = false" class="ml-auto p-1 opacity-60 hover:opacity-100 transition cursor-pointer">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Top Bar Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('dashboard') }}" wire:navigate
            class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-indigo-600 dark:text-neutral-400 dark:hover:text-indigo-400 transition-colors group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-800/80 shadow-xs uppercase tracking-wider">
                <span class="size-2 rounded-full bg-indigo-500 animate-ping"></span>
                AI Audio Suite
            </span>
        </div>
    </div>

    {{-- Hero Banner --}}
    <div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 space-y-3 max-w-3xl">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-white/10 text-indigo-200 border border-white/20 backdrop-blur-md">
                    🎤 Text & Document to Speech
                </span>
                <span class="text-xs font-bold text-purple-200 bg-purple-900/40 px-2.5 py-1 rounded-full border border-purple-400/30">
                    Edge TTS Neural Voices
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white leading-tight">
                AI Audio Generator
            </h1>
            <p class="text-indigo-200 text-sm md:text-base leading-relaxed">
                Paste text, upload a document (PDF, DOCX, TXT, PPTX), or select an existing learning material to generate high-quality AI neural speech.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none hidden sm:block">
            <svg class="size-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
            </svg>
        </div>
    </div>

    {{-- Active Processing Card (polls when there are pending/processing items) --}}
    @if($hasProcessing)
        <div wire:poll.3s="pollAudioList"
            class="p-6 bg-gradient-to-r from-indigo-950 via-slate-900 to-purple-950 text-white rounded-3xl shadow-lg border border-indigo-500/30 space-y-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-indigo-500/20 text-indigo-300 rounded-2xl border border-indigo-400/30 shrink-0">
                    <svg class="animate-spin size-6 text-indigo-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        Audio Generation in Progress
                        <span class="px-2.5 py-0.5 text-[10px] uppercase tracking-wider font-extrabold bg-indigo-500/40 text-indigo-200 rounded-full border border-indigo-400/30">Processing</span>
                    </h3>
                    <p class="text-xs text-indigo-200 mt-0.5">Your audio is being generated. This page will update automatically.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Audio Generator Form (2 cols) --}}
        <div class="lg:col-span-2 bg-white dark:bg-neutral-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200 dark:border-neutral-800 space-y-6">

            {{-- Source Selection Mode Tabs --}}
            <div class="flex items-center p-1 bg-neutral-100 dark:bg-neutral-950 rounded-2xl gap-1">
                <button type="button" wire:click="$set('sourceMode', 'text')"
                    class="flex-1 py-2.5 px-3 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer {{ $sourceMode === 'text' ? 'bg-white dark:bg-neutral-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200' }}">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Paste Text</span>
                </button>
                <button type="button" wire:click="$set('sourceMode', 'file')"
                    class="flex-1 py-2.5 px-3 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer {{ $sourceMode === 'file' ? 'bg-white dark:bg-neutral-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200' }}">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>Upload Document</span>
                </button>
                <button type="button" wire:click="$set('sourceMode', 'material')"
                    class="flex-1 py-2.5 px-3 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer {{ $sourceMode === 'material' ? 'bg-white dark:bg-neutral-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200' }}">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>My Library ({{ count($existingMaterials) }})</span>
                </button>
            </div>

            <form wire:submit="generate" class="space-y-5">
                {{-- Mode 1: Document Upload Dropzone --}}
                @if($sourceMode === 'file')
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Document File <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative border-2 border-dashed border-neutral-200 dark:border-neutral-800 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl p-6 transition-all bg-neutral-50/50 dark:bg-neutral-950/40 text-center group cursor-pointer">
                            <input type="file" wire:model="file" accept=".pdf,.doc,.docx,.txt,.pptx,.ppt"
                                @change="fileName = $event.target.files[0]?.name || ''; fileSize = $event.target.files[0] ? (($event.target.files[0].size / 1024).toFixed(1) + ' KB') : ''"
                                class="absolute inset-0 size-full opacity-0 cursor-pointer z-10" />
                            <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl group-hover:scale-110 transition-transform">
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <div class="text-xs font-bold text-neutral-800 dark:text-neutral-200">
                                    <span>Click to browse <span class="font-normal text-neutral-500">or drag & drop</span></span>
                                </div>
                                <p class="text-[11px] text-neutral-400 dark:text-neutral-500">Supported: PDF, DOCX, TXT, PPTX (Max 20MB)</p>
                            </div>
                        </div>

                        {{-- File Extraction Progress Indicator --}}
                        <div wire:loading wire:target="file" class="p-3 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 rounded-xl flex items-center gap-3">
                            <svg class="animate-spin size-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs text-indigo-700 dark:text-indigo-300 font-semibold">Extracting text from document...</span>
                        </div>

                        @error('file')
                            <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror

                        <div x-show="fileName" x-cloak
                            class="p-3 bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-2.5 truncate">
                                <div class="p-1.5 bg-indigo-600 text-white rounded-lg text-[10px] font-bold uppercase shrink-0">
                                    <span x-text="fileName.split('.').pop()"></span>
                                </div>
                                <div class="truncate">
                                    <span class="text-xs font-bold text-indigo-950 dark:text-indigo-200 truncate block" x-text="fileName"></span>
                                    <span class="text-[11px] text-indigo-600 dark:text-indigo-400 block">Size: <span x-text="fileSize"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Mode 2: Select Existing Material Dropdown --}}
                @if($sourceMode === 'material')
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Choose Learning Material <span class="text-rose-500">*</span>
                        </label>
                        @if(count($existingMaterials) > 0)
                            <select wire:model.live="selectedMaterialId"
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                @disabled($isGenerating)>
                                <option value="">-- Select a material from your library --</option>
                                @foreach($existingMaterials as $mat)
                                    <option value="{{ $mat->id }}">
                                        {{ $mat->title }} ({{ strtoupper($mat->file_type) }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 rounded-2xl text-xs">
                                No learning materials in your library yet. Switch to <strong>Upload Document</strong> to add one!
                            </div>
                        @endif
                        @error('selectedMaterialId')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                {{-- Title Input --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Audio Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="title" placeholder="e.g., Chapter 5 Lecture Notes"
                        class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                        @disabled($isGenerating) />
                    @error('title')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Text Input --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Text to Convert <span class="text-rose-500">*</span>
                    </label>
                    <textarea wire:model="textInput" rows="5"
                        placeholder="{{ $sourceMode === 'file' ? 'Extracted text from your document will appear here...' : 'Paste your text or study notes here (max 50,000 characters)...' }}"
                        class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none"
                        @disabled($isGenerating)></textarea>
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] text-neutral-400 dark:text-neutral-500">Minimum 10 characters</p>
                        <p class="text-[11px] font-semibold {{ strlen($textInput) > 50000 ? 'text-rose-500' : 'text-neutral-400 dark:text-neutral-500' }}">
                            {{ strlen($textInput) }} / 50,000
                        </p>
                    </div>
                    @error('textInput')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Audio Format / Generation Mode Selection --}}
                <div class="space-y-2 p-4 bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40 rounded-2xl">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Audio Format <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 bg-white dark:bg-neutral-900 border rounded-xl cursor-pointer transition-all {{ $generationMode === 'solo' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-neutral-200 dark:border-neutral-800' }}">
                            <input type="radio" wire:model.live="generationMode" value="solo" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="text-xs font-bold text-neutral-900 dark:text-neutral-100 block">🎙️ Solo Narration</span>
                                <span class="text-[11px] text-neutral-500 dark:text-neutral-400 block">Single voice narrator reading structured text</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-white dark:bg-neutral-900 border rounded-xl cursor-pointer transition-all {{ $generationMode === 'podcast' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-neutral-200 dark:border-neutral-800' }}">
                            <input type="radio" wire:model.live="generationMode" value="podcast" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 block">🎙️🎙️🎙️ 3-Person Podcast</span>
                                <span class="text-[11px] text-neutral-500 dark:text-neutral-400 block">Host + Expert + Student interactive dialogue</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Voice Settings Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Language --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">Language</label>
                        <select wire:model.live="selectedLanguage"
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            @disabled($isGenerating)>
                            @foreach($languages as $lang)
                                <option value="{{ $lang }}">{{ $lang }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Gender --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">Voice Gender</label>
                        <select wire:model.live="selectedGender"
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            @disabled($isGenerating)>
                            @foreach($availableGenders as $gender)
                                <option value="{{ $gender }}">{{ ucfirst($gender) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Speaking Style --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">Speaking Style</label>
                    <select wire:model="selectedStyle"
                        class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                        @disabled($isGenerating)>
                        @foreach($styles as $styleKey => $styleLabel)
                            <option value="{{ $styleKey }}">{{ ucfirst(str_replace('-', ' ', $styleKey)) }} — {{ $styleLabel }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Advanced Settings Toggle --}}
                <div>
                    <button type="button" wire:click="$toggle('showAdvanced')"
                        class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors cursor-pointer">
                        <svg class="size-4 transition-transform {{ $showAdvanced ? 'rotate-90' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Advanced Voice Settings
                    </button>
                </div>

                @if($showAdvanced)
                    <div class="p-5 bg-neutral-50 dark:bg-neutral-950/60 border border-neutral-200 dark:border-neutral-800 rounded-2xl space-y-5">
                        {{-- Speech Rate --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                                Speech Rate
                            </label>
                            <input type="range" wire:model.live="speechRate" min="0.5" max="2.0" step="0.1"
                                class="w-full h-2 bg-neutral-200 dark:bg-neutral-700 rounded-full appearance-none cursor-pointer accent-indigo-600">
                            <div class="flex justify-between text-[11px] text-neutral-500 dark:text-neutral-400">
                                <span>🐢 Slower (0.5x)</span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400 text-sm">{{ $speechRate }}x</span>
                                <span>🐇 Faster (2.0x)</span>
                            </div>
                        </div>

                        {{-- Pitch --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                                Pitch Adjustment
                            </label>
                            <input type="range" wire:model.live="pitch" min="-50" max="50" step="5"
                                class="w-full h-2 bg-neutral-200 dark:bg-neutral-700 rounded-full appearance-none cursor-pointer accent-indigo-600">
                            <div class="flex justify-between text-[11px] text-neutral-500 dark:text-neutral-400">
                                <span>Lower (-50)</span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400 text-sm">{{ $pitch > 0 ? '+' : '' }}{{ $pitch }}</span>
                                <span>Higher (+50)</span>
                            </div>
                        </div>

                        {{-- Save Preferences --}}
                        <button type="button" wire:click="savePreferences"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 rounded-xl text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-950 transition-colors cursor-pointer">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Save as Default Preferences
                        </button>
                    </div>
                @endif

                {{-- Generate Button --}}
                <div class="pt-2 flex justify-end">
                    <button type="submit" @disabled($isGenerating)
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 disabled:opacity-50 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-200 dark:shadow-none transition-all cursor-pointer">
                        <div wire:loading.remove wire:target="generate" class="flex items-center gap-2">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                            <span>Generate Audio</span>
                        </div>
                        <div wire:loading wire:target="generate" class="flex items-center gap-2">
                            <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Queuing...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>

        {{-- Voice Preview Sidebar (1 col) --}}
        <div class="space-y-6">
            {{-- Current Voice Config --}}
            <div class="bg-white dark:bg-neutral-900 rounded-3xl p-6 shadow-sm border border-neutral-200 dark:border-neutral-800 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-xl">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-neutral-900 dark:text-neutral-100">Voice Configuration</h3>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 dark:border-neutral-800">
                        <span class="text-neutral-500 dark:text-neutral-400 text-xs font-medium">Language</span>
                        <span class="font-semibold text-neutral-900 dark:text-neutral-100 text-xs">{{ $selectedLanguage }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 dark:border-neutral-800">
                        <span class="text-neutral-500 dark:text-neutral-400 text-xs font-medium">Gender</span>
                        <span class="font-semibold text-neutral-900 dark:text-neutral-100 text-xs">{{ ucfirst($selectedGender) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 dark:border-neutral-800">
                        <span class="text-neutral-500 dark:text-neutral-400 text-xs font-medium">Voice</span>
                        <span class="font-semibold text-indigo-600 dark:text-indigo-400 text-xs font-mono">{{ $selectedVoice }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-neutral-100 dark:border-neutral-800">
                        <span class="text-neutral-500 dark:text-neutral-400 text-xs font-medium">Style</span>
                        <span class="px-2 py-0.5 text-xs font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-full">{{ ucfirst(str_replace('-', ' ', $selectedStyle)) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-neutral-500 dark:text-neutral-400 text-xs font-medium">Speed</span>
                        <span class="font-semibold text-neutral-900 dark:text-neutral-100 text-xs">{{ $speechRate }}x</span>
                    </div>
                </div>
            </div>

            {{-- Speaking Styles Guide --}}
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 text-white rounded-3xl p-6 shadow-md relative overflow-hidden">
                <div class="relative z-10 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-md">
                        <svg class="size-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Speaking Styles
                    </div>
                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="font-bold text-white">📰 Newscast</span>
                            <p class="text-indigo-200 mt-0.5 leading-relaxed">Professional news presenter — great for tutorials</p>
                        </div>
                        <div>
                            <span class="font-bold text-white">📖 Instruction</span>
                            <p class="text-indigo-200 mt-0.5 leading-relaxed">Clear instructional tone — best for step-by-step guides</p>
                        </div>
                        <div>
                            <span class="font-bold text-white">😊 Cheering</span>
                            <p class="text-indigo-200 mt-0.5 leading-relaxed">Enthusiastic and engaging — ideal for introductions</p>
                        </div>
                        <div>
                            <span class="font-bold text-white">💬 Customer Service</span>
                            <p class="text-indigo-200 mt-0.5 leading-relaxed">Friendly and helpful tone — for Q&A content</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Audio Library --}}
    <div class="bg-white dark:bg-neutral-900 rounded-3xl shadow-sm border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-neutral-100 dark:border-neutral-800">
            <div class="flex items-center gap-3.5">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl shrink-0">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">My Audio Library</h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ count($audioList) }} audio file{{ count($audioList) !== 1 ? 's' : '' }} generated</p>
                </div>
            </div>
        </div>

        @if(count($audioList) > 0)
            <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @foreach($audioList as $audio)
                    <div class="p-5 sm:p-6 hover:bg-neutral-50/50 dark:hover:bg-neutral-800/30 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            {{-- Left: Info --}}
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                {{-- Status Icon --}}
                                <div class="shrink-0 mt-0.5">
                                    @if($audio['status'] === 'completed')
                                        <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl">
                                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @elseif($audio['status'] === 'processing')
                                        <div class="p-2.5 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-xl">
                                            <svg class="animate-spin size-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    @elseif($audio['status'] === 'failed')
                                        <div class="p-2.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-xl">
                                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @else
                                        <div class="p-2.5 bg-neutral-100 dark:bg-neutral-800 text-neutral-400 rounded-xl">
                                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Title & Metadata --}}
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-bold text-neutral-900 dark:text-neutral-100 truncate">{{ $audio['title'] }}</h3>
                                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                        <span class="text-[11px] text-neutral-400 dark:text-neutral-500 font-medium">{{ $audio['voice'] }}</span>
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-full">{{ ucfirst(str_replace('-', ' ', $audio['style'])) }}</span>
                                        @if(($audio['generationMode'] ?? 'solo') === 'podcast')
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-full border border-purple-200/80 dark:border-purple-800/80">🎙️ 3-Person Podcast</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 rounded-full">Solo Narration</span>
                                        @endif
                                        @if($audio['formattedDuration'])
                                            <span class="text-[11px] text-neutral-400 dark:text-neutral-500">⏱ {{ $audio['formattedDuration'] }}</span>
                                        @endif
                                        @if($audio['fileSize'])
                                            <span class="text-[11px] text-neutral-400 dark:text-neutral-500">📁 {{ $audio['fileSize'] }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <span class="text-[11px] text-neutral-400 dark:text-neutral-500">{{ $audio['createdAt'] }} · {{ $audio['createdAtTime'] }}</span>
                                        @if($audio['plays'] > 0)
                                            <span class="text-[11px] text-neutral-400 dark:text-neutral-500">🔊 {{ $audio['plays'] }} play{{ $audio['plays'] !== 1 ? 's' : '' }}</span>
                                        @endif
                                    </div>
                                    @if($audio['status'] === 'failed' && $audio['error'])
                                        <p class="text-xs text-rose-500 dark:text-rose-400 mt-1.5 bg-rose-50 dark:bg-rose-950/40 px-3 py-1.5 rounded-lg">
                                            ⚠️ {{ $audio['error'] }}
                                        </p>
                                    @endif

                                    @if(!empty($audio['aiScript']))
                                        <div x-data="{ showScript: false }" class="mt-2">
                                            <button @click="showScript = !showScript"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">
                                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span x-text="showScript ? 'Hide AI Script' : 'View Gemini AI Audio Script'"></span>
                                            </button>
                                            <div x-show="showScript" x-cloak class="mt-2 p-3 bg-indigo-50/60 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 rounded-xl text-xs text-neutral-800 dark:text-neutral-200 leading-relaxed font-sans whitespace-pre-wrap max-h-48 overflow-y-auto">
                                                {{ $audio['aiScript'] }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Right: Actions --}}
                            <div class="flex items-center gap-2 shrink-0">
                                @if($audio['status'] === 'completed')
                                    <button wire:click="playAudio({{ $audio['id'] }})"
                                        class="p-2.5 rounded-xl {{ $currentPlaying === $audio['id'] ? 'bg-indigo-600 text-white' : 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-950' }} transition-colors cursor-pointer"
                                        title="{{ $currentPlaying === $audio['id'] ? 'Close Player' : 'Play Audio' }}">
                                        @if($currentPlaying === $audio['id'])
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @else
                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        @endif
                                    </button>
                                    <a href="{{ route('audio.download', $audio['id']) }}"
                                        class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-950 transition-colors"
                                        title="Download">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                @endif
                                @if($audio['status'] === 'failed')
                                    <button wire:click="retryAudio({{ $audio['id'] }})"
                                        class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950 transition-colors cursor-pointer"
                                        title="Retry">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                @endif
                                <button wire:click="deleteAudio({{ $audio['id'] }})"
                                    wire:confirm="Are you sure you want to delete this audio?"
                                    class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-950 transition-colors cursor-pointer"
                                    title="Delete">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Inline Audio Player --}}
                        @if($audio['status'] === 'completed' && $currentPlaying === $audio['id'])
                            <div class="mt-4 p-4 bg-neutral-50 dark:bg-neutral-950/60 rounded-2xl border border-neutral-200 dark:border-neutral-800">
                                <audio controls autoplay class="w-full" style="height: 40px;">
                                    <source src="{{ route('audio.stream', $audio['id']) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="p-4 bg-neutral-100 dark:bg-neutral-800 rounded-2xl inline-block mb-4">
                    <svg class="size-8 text-neutral-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-neutral-600 dark:text-neutral-300">No Audio Files Yet</h3>
                <p class="text-xs text-neutral-400 dark:text-neutral-500 mt-1">Generate your first audio from the form above!</p>
            </div>
        @endif
    </div>
</div>