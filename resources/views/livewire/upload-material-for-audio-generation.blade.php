<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto space-y-8 w-full overflow-hidden"
    x-data="{ submitting: false, fileName: '', fileSize: '' }">

    {{-- Top Bar Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('dashboard') }}" wire:navigate
            class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-indigo-600 dark:text-neutral-400 dark:hover:text-indigo-400 transition-colors group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
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

    {{-- Full-Screen Processing Modal --}}
    <div x-show="submitting" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-950/80 backdrop-blur-md p-4 transition-all">
        <div class="bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 text-white border border-indigo-500/30 rounded-3xl p-8 max-w-lg w-full shadow-2xl space-y-6 relative overflow-hidden text-center">
            <div class="absolute -top-12 -right-12 size-40 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 size-40 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative mx-auto size-20 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-500/20 border-t-indigo-400 border-r-purple-400 animate-spin"></div>
                <div class="p-4 bg-indigo-600/30 rounded-full border border-indigo-400/40 text-indigo-300">
                    <svg class="size-8 animate-pulse text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
            </div>

            <div class="space-y-2">
                <h3 class="text-xl font-extrabold tracking-tight text-white">Audio Generation in Progress...</h3>
                <p class="text-xs text-indigo-200 leading-relaxed max-w-sm mx-auto">
                    Extracting document text and generating high-quality neural speech audio.
                </p>
            </div>

            <div class="p-4 bg-white/5 rounded-2xl border border-white/10 text-left space-y-3 text-xs">
                <div class="flex items-center gap-3 text-emerald-400 font-semibold">
                    <div class="size-5 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>1. Material selected & text extracted</span>
                </div>
                <div class="flex items-center gap-3 text-indigo-300 font-semibold">
                    <div class="size-5 rounded-full bg-indigo-500/30 flex items-center justify-center shrink-0">
                        <svg class="animate-spin size-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <span>2. Generating Edge TTS speech...</span>
                </div>
                <div class="flex items-center gap-3 text-neutral-400">
                    <div class="size-5 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                        <span class="size-1.5 rounded-full bg-neutral-400"></span>
                    </div>
                    <span>3. MP3 ready for streaming & download</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="w-full bg-white/10 h-2.5 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-400 rounded-full animate-pulse w-4/5"></div>
                </div>
                <p class="text-[11px] text-indigo-300 font-medium">Please do not refresh or close this page</p>
            </div>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 rounded-xl shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 transition-colors p-1 cursor-pointer">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-400 rounded-xl shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-rose-600 dark:text-rose-400 hover:text-rose-800 transition-colors p-1 cursor-pointer">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    {{-- Hero Banner --}}
    <div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 space-y-3 max-w-3xl">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-white/10 text-indigo-200 border border-white/20 backdrop-blur-md">
                    🎤 Material to Audio
                </span>
                <span class="text-xs font-bold text-purple-200 bg-purple-900/40 px-2.5 py-1 rounded-full border border-purple-400/30">
                    PDF, DOCX, PPTX, TXT
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white leading-tight">
                Document Audio Generator
            </h1>
            <p class="text-indigo-200 text-sm md:text-base leading-relaxed">
                Upload a document or select an existing learning material, extract its text, and turn it into audio using AI voices. Listen and review anywhere.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none hidden sm:block">
            <svg class="size-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
            </svg>
        </div>
    </div>

    {{-- Processing Card (polls when audio is being generated) --}}
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

    {{-- Content Grid: Form + How it Works --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Area --}}
        <div class="lg:col-span-2 bg-white dark:bg-neutral-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200 dark:border-neutral-800 space-y-6">

            {{-- Mode Selection Tabs --}}
            <div class="flex items-center p-1 bg-neutral-100 dark:bg-neutral-950 rounded-2xl">
                <button type="button" wire:click="$set('uploadMode', 'new')"
                    class="flex-1 py-2.5 px-4 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer {{ $uploadMode === 'new' ? 'bg-white dark:bg-neutral-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200' }}">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>Upload New Document</span>
                </button>
                <button type="button" wire:click="$set('uploadMode', 'existing')"
                    class="flex-1 py-2.5 px-4 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer {{ $uploadMode === 'existing' ? 'bg-white dark:bg-neutral-800 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200' }}">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Select Existing Material ({{ count($existingMaterials) }})</span>
                </button>
            </div>

            <form wire:submit.prevent="generateAudio" enctype="multipart/form-data" class="space-y-5"
                @submit="submitting = true">

                @if($uploadMode === 'existing')
                    {{-- Select Existing Material Dropdown --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Choose Learning Material <span class="text-rose-500">*</span>
                        </label>
                        @if(count($existingMaterials) > 0)
                            <select wire:model="selectedMaterialId"
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="">-- Select a material from your library --</option>
                                @foreach($existingMaterials as $mat)
                                    <option value="{{ $mat->id }}">
                                        {{ $mat->title }} ({{ strtoupper($mat->file_type) }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 rounded-2xl text-xs">
                                No learning materials found in your library yet. Switch to <strong>Upload New Document</strong> to add one!
                            </div>
                        @endif
                        @error('selectedMaterialId')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    {{-- Title Input --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Material Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="title" placeholder="e.g., Chapter 4 Lecture Notes"
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" />
                        @error('title')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description Input --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Description <span class="text-neutral-400 font-normal text-xs">(Optional)</span>
                        </label>
                        <textarea wire:model="description" rows="2" placeholder="Add study notes or context..."
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none"></textarea>
                        @error('description')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- File Upload Dropzone --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Document File <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative border-2 border-dashed border-neutral-200 dark:border-neutral-800 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl p-6 sm:p-8 transition-all bg-neutral-50/50 dark:bg-neutral-950/40 text-center group cursor-pointer">
                            <input type="file" wire:model="file" accept=".pdf,.doc,.docx,.txt,.pptx,.ppt"
                                @change="fileName = $event.target.files[0]?.name || ''; fileSize = $event.target.files[0] ? (($event.target.files[0].size / 1024).toFixed(1) + ' KB') : ''"
                                class="absolute inset-0 size-full opacity-0 cursor-pointer z-10" />
                            <div class="flex flex-col items-center justify-center space-y-2.5 pointer-events-none">
                                <div class="p-3.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl group-hover:scale-110 transition-transform">
                                    <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <div class="text-xs sm:text-sm font-bold text-neutral-800 dark:text-neutral-200">
                                    <span>Click to browse files <span class="font-normal text-neutral-500">or drag & drop</span></span>
                                </div>
                                <p class="text-[11px] text-neutral-400 dark:text-neutral-500">Supported: PDF, DOCX, TXT, PPTX (Max 20MB)</p>
                            </div>
                        </div>

                        @error('file')
                            <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Selected File Preview --}}
                        <div x-show="fileName" x-cloak
                            class="p-4 bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3 truncate">
                                <div class="p-2 bg-indigo-600 text-white rounded-lg text-xs font-bold uppercase shrink-0">
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
                                <span class="text-[11px] text-neutral-500 dark:text-neutral-400 block">Single voice narrator reading document text</span>
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

                {{-- Voice Settings --}}
                <div class="p-5 bg-neutral-50 dark:bg-neutral-950/60 border border-neutral-200 dark:border-neutral-800 rounded-2xl space-y-4">
                    <h3 class="text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider flex items-center gap-2">
                        <svg class="size-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        </svg>
                        Voice Settings
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-semibold text-neutral-500 dark:text-neutral-400">Language</label>
                            <select wire:model.live="selectedLanguage"
                                class="w-full px-3 py-2.5 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                @foreach($languages as $lang)
                                    <option value="{{ $lang }}">{{ $lang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-semibold text-neutral-500 dark:text-neutral-400">Gender</label>
                            <select wire:model.live="selectedGender"
                                class="w-full px-3 py-2.5 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                @foreach($availableGenders as $gender)
                                    <option value="{{ $gender }}">{{ ucfirst($gender) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-semibold text-neutral-500 dark:text-neutral-400">Style</label>
                            <select wire:model="selectedStyle"
                                class="w-full px-3 py-2.5 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                @foreach($styles as $styleKey => $styleLabel)
                                    <option value="{{ $styleKey }}">{{ ucfirst(str_replace('-', ' ', $styleKey)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2 flex justify-end">
                    <button type="submit" :disabled="submitting"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 disabled:opacity-50 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-200 dark:shadow-none transition-all cursor-pointer">
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                                <span>Extract Text & Generate Audio</span>
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Extracting & Queuing Audio...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>

        {{-- How It Works Sidebar --}}
        <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden flex flex-col justify-between space-y-6">
            <div class="space-y-4 relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-md">
                    <svg class="size-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span>How Audio Generation Works</span>
                </div>

                <div class="space-y-3.5 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">1</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Upload or Select Material</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">Upload a PDF/Word/PPT document or pick from your material library.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">2</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Text Extraction</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">Text Parser extracts readable content from your document automatically.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">3</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Neural TTS Speech</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">Edge TTS turns the extracted text into natural neural voice audio.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">4</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Listen & Download MP3</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">Play audio directly in browser or download MP3 files.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 relative z-10 flex items-center justify-between text-xs text-indigo-300 font-medium">
                <span>Supports PDF, DOCX, PPTX, TXT</span>
                <span>Max 50,000 chars per audio</span>
            </div>
        </div>
    </div>

    {{-- Audio Library --}}
    @if(count($audioList) > 0)
        <div class="bg-white dark:bg-neutral-900 rounded-3xl shadow-sm border border-neutral-200 dark:border-neutral-800 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-neutral-100 dark:border-neutral-800">
                <div class="flex items-center gap-3.5">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl shrink-0">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">Generated Audio Files</h2>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ count($audioList) }} audio file{{ count($audioList) !== 1 ? 's' : '' }}</p>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @foreach($audioList as $audio)
                    <div class="p-5 sm:p-6 hover:bg-neutral-50/50 dark:hover:bg-neutral-800/30 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <div class="shrink-0 mt-0.5">
                                    @if($audio['status'] === 'completed')
                                        <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl">
                                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @elseif($audio['status'] === 'processing')
                                        <div class="p-2.5 bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 rounded-xl">
                                            <svg class="animate-spin size-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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
                                    </div>
                                    <span class="text-[11px] text-neutral-400 dark:text-neutral-500 mt-1 block">{{ $audio['createdAt'] }} · {{ $audio['createdAtTime'] }}</span>
                                    @if($audio['status'] === 'failed' && $audio['error'])
                                        <p class="text-xs text-rose-500 dark:text-rose-400 mt-1.5 bg-rose-50 dark:bg-rose-950/40 px-3 py-1.5 rounded-lg">⚠️ {{ $audio['error'] }}</p>
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

                            <div class="flex items-center gap-2 shrink-0">
                                @if($audio['status'] === 'completed')
                                    <button wire:click="playAudio({{ $audio['id'] }})"
                                        class="p-2.5 rounded-xl {{ $currentPlaying === $audio['id'] ? 'bg-indigo-600 text-white' : 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-950' }} transition-colors cursor-pointer"
                                        title="{{ $currentPlaying === $audio['id'] ? 'Close' : 'Play' }}">
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
                                    <button wire:click="retryAudio({{ $audio['id'] }})" class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-950 transition-colors cursor-pointer" title="Retry">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                @endif
                                <button wire:click="deleteAudio({{ $audio['id'] }})" wire:confirm="Are you sure you want to delete this audio?"
                                    class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-950 transition-colors cursor-pointer" title="Delete">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

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
        </div>
    @endif
</div>