<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto space-y-8 w-full overflow-hidden" x-data="{ fileName: '', fileSize: '', uploadMode: 'new' }">
    
    <!-- Top Bar Navigation & Badges -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('dashboard') }}" wire:navigate
            class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-indigo-600 dark:text-neutral-400 dark:hover:text-indigo-400 transition-colors group">
            <svg class="size-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-full bg-purple-50 text-purple-700 dark:bg-purple-950/80 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800/80 shadow-xs uppercase tracking-wider">
                <span class="size-2 rounded-full bg-purple-500 animate-ping"></span>
                AI Quiz Generator
            </span>
        </div>
    </div>

    <!-- FULL-SCREEN LOADING OVERLAY DURING QUIZ GENERATION -->
    <div wire:loading wire:target="generateQuiz" class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-950/80 backdrop-blur-md p-4 transition-all">
        <div class="bg-gradient-to-br from-purple-950 via-slate-900 to-indigo-950 text-white border border-purple-500/30 rounded-3xl p-8 max-w-lg w-full shadow-2xl space-y-6 relative overflow-hidden text-center">
            <div class="absolute -top-12 -right-12 size-40 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 size-40 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative mx-auto size-20 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-purple-500/20 border-t-purple-400 border-r-indigo-400 animate-spin"></div>
                <div class="p-4 bg-purple-600/30 rounded-full border border-purple-400/40 text-purple-300">
                    <svg class="size-8 animate-pulse text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
            </div>

            <div class="space-y-2">
                <h3 class="text-xl font-extrabold tracking-tight text-white flex items-center justify-center gap-2">
                    Generating {{ $numQuestions }}-Question AI Quiz...
                </h3>
                <p class="text-xs text-purple-200 leading-relaxed max-w-sm mx-auto">
                    Gemini AI is parsing the document text, formulating questions, choices, and detailed explanations.
                </p>
            </div>

            <div class="space-y-1.5">
                <div class="w-full bg-white/10 h-2.5 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-gradient-to-r from-purple-500 via-indigo-500 to-emerald-400 rounded-full animate-pulse w-4/5"></div>
                </div>
                <p class="text-[11px] text-purple-300 font-medium">Please do not refresh or navigate away...</p>
            </div>
        </div>
    </div>

    <!-- Flash Notifications -->
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
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Hero Banner Card -->
    <div class="bg-gradient-to-br from-purple-950 via-slate-900 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 space-y-3 max-w-3xl">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-white/10 text-purple-200 border border-white/20 backdrop-blur-md">
                    ✨ Instant Assessment Generator
                </span>
                <span class="text-xs font-bold text-indigo-200 bg-indigo-900/40 px-2.5 py-1 rounded-full border border-indigo-400/30">
                    PDF, DOCX, PPTX, TXT
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white leading-tight">
                AI Quiz Generator
            </h1>
            <p class="text-purple-200 text-sm md:text-base leading-relaxed">
                Upload your lecture notes or study material, customize the number of questions, and generate an interactive self-assessment quiz powered by Gemini AI.
            </p>
        </div>
    </div>

    <!-- Main Grid: Generator Form & Instructions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Area (2 Columns) -->
        <div class="lg:col-span-2 bg-white dark:bg-neutral-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200 dark:border-neutral-800 space-y-6">
            
            <!-- Source Material Selection Mode Tabs -->
            <div class="flex items-center p-1 bg-neutral-100 dark:bg-neutral-950 rounded-2xl">
                <button type="button" @click="uploadMode = 'new'"
                    :class="uploadMode === 'new' ? 'bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white shadow-xs' : 'text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200'"
                    class="flex-1 py-2.5 px-4 text-xs font-bold rounded-xl transition-all text-center cursor-pointer">
                    Upload New Document
                </button>
                @if (count($materials) > 0)
                    <button type="button" @click="uploadMode = 'existing'"
                        :class="uploadMode === 'existing' ? 'bg-white dark:bg-neutral-800 text-neutral-900 dark:text-white shadow-xs' : 'text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-200'"
                        class="flex-1 py-2.5 px-4 text-xs font-bold rounded-xl transition-all text-center cursor-pointer">
                        Select Uploaded Material ({{ count($materials) }})
                    </button>
                @endif
            </div>

            <form wire:submit="generateQuiz" class="space-y-6">
                
                <!-- NEW UPLOAD MODE -->
                <div x-show="uploadMode === 'new'" class="space-y-5">
                    <!-- Title Input -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Material / Quiz Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="title" placeholder="e.g. Operating Systems - Memory Management Notes"
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        @error('title')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Dropzone -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Document File <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative border-2 border-dashed border-neutral-200 dark:border-neutral-800 hover:border-purple-500 dark:hover:border-purple-400 rounded-2xl p-6 sm:p-8 transition-all bg-neutral-50/50 dark:bg-neutral-950/40 text-center group cursor-pointer">
                            <input type="file" wire:model="file" accept=".pdf,.doc,.docx,.txt,.pptx,.ppt"
                                @change="fileName = $event.target.files[0]?.name || ''; fileSize = $event.target.files[0] ? (($event.target.files[0].size / 1024).toFixed(1) + ' KB') : ''"
                                class="absolute inset-0 size-full opacity-0 cursor-pointer z-10" />
                            <div class="flex flex-col items-center justify-center space-y-2.5 pointer-events-none">
                                <div class="p-3.5 bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-2xl group-hover:scale-110 transition-transform">
                                    <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <div class="text-xs sm:text-sm font-bold text-neutral-800 dark:text-neutral-200">
                                    <span>Click to browse files <span class="font-normal text-neutral-500">or drag & drop</span></span>
                                </div>
                                <p class="text-[11px] text-neutral-400 dark:text-neutral-500">Supported formats: PDF, DOCX, TXT, PPTX (Max 20MB)</p>
                            </div>
                        </div>

                        @error('file')
                            <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Selected File Preview -->
                        <div x-show="fileName" x-cloak
                             class="p-4 bg-purple-50/70 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/60 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3 truncate">
                                <div class="p-2 bg-purple-600 text-white rounded-lg text-xs font-bold uppercase shrink-0">
                                    <span x-text="fileName.split('.').pop()"></span>
                                </div>
                                <div class="truncate">
                                    <span class="text-xs font-bold text-purple-950 dark:text-purple-200 truncate block" x-text="fileName"></span>
                                    <span class="text-[11px] text-purple-600 dark:text-purple-400 block">Size: <span x-text="fileSize"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EXISTING MATERIAL SELECT MODE -->
                @if (count($materials) > 0)
                    <div x-show="uploadMode === 'existing'" class="space-y-2">
                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            Choose Uploaded Material <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model="selectedMaterialId"
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                            <option value="">-- Select a material --</option>
                            @foreach ($materials as $item)
                                <option value="{{ $item->id }}">{{ $item->title }} ({{ $item->original_filename }})</option>
                            @endforeach
                        </select>
                        @error('selectedMaterialId')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Question Count Selector -->
                <div class="space-y-3 pt-2 border-t border-neutral-100 dark:border-neutral-800">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Number of Questions <span class="text-rose-500">*</span>
                    </label>

                    <!-- Quick Select Pills -->
                    <div class="grid grid-cols-5 gap-2">
                        @foreach ([3, 5, 8, 10, 15] as $count)
                            <button type="button" wire:click="$set('numQuestions', {{ $count }})"
                                class="py-2.5 px-2 rounded-2xl text-xs font-extrabold border transition-all text-center cursor-pointer {{ $numQuestions == $count ? 'bg-purple-600 text-white border-purple-600 shadow-md shadow-purple-200 dark:shadow-none' : 'bg-neutral-50 dark:bg-neutral-950 text-neutral-700 dark:text-neutral-300 border-neutral-200 dark:border-neutral-800 hover:border-purple-400' }}">
                                {{ $count }}
                                <span class="block text-[10px] font-medium opacity-80">Questions</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Range Slider -->
                    <div class="pt-2 flex items-center gap-3">
                        <input type="range" wire:model.live="numQuestions" min="1" max="20"
                            class="w-full accent-purple-600 cursor-pointer h-2 bg-neutral-200 dark:bg-neutral-800 rounded-lg" />
                        <div class="w-16 px-3 py-1.5 bg-neutral-100 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-center text-xs font-bold text-neutral-900 dark:text-white">
                            {{ $numQuestions }} Qs
                        </div>
                    </div>
                    @error('numQuestions')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Action Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-purple-200 dark:shadow-none transition-all cursor-pointer">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        <span>Extract & Generate {{ $numQuestions }}-Question Quiz</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: How it Works Card (1 Column) -->
        <div class="bg-gradient-to-br from-purple-900 via-slate-900 to-indigo-950 text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden flex flex-col justify-between space-y-6">
            <div class="space-y-4 relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-md">
                    <svg class="size-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>Quiz Workflow</span>
                </div>

                <div class="space-y-3.5 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-purple-500/30 text-purple-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-purple-400/30">1</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Upload Material</h4>
                            <p class="text-[11px] text-purple-200 leading-relaxed">Upload PDF, DOCX, TXT, or PPTX slides.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-purple-500/30 text-purple-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-purple-400/30">2</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Automated Text Extraction</h4>
                            <p class="text-[11px] text-purple-200 leading-relaxed">Extracts key concepts, definitions, and lecture notes.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-purple-500/30 text-purple-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-purple-400/30">3</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Set Question Count</h4>
                            <p class="text-[11px] text-purple-200 leading-relaxed">Choose 3 to 20 questions based on study needs.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-purple-500/30 text-purple-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-purple-400/30">4</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Take Quiz & Review</h4>
                            <p class="text-[11px] text-purple-200 leading-relaxed">Interactive web quiz with score and wrong answer explanations.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 relative z-10 flex items-center justify-between text-xs text-purple-300 font-medium">
                <span>Fast & Interactive</span>
                <span>Powered by Gemini AI</span>
            </div>
        </div>
    </div>

    <!-- List of Quizzes Filament Table -->
    <livewire:list-of-quizzes />
</div>

