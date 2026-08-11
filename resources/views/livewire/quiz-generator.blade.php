<div class="p-4 sm:p-6 md:p-8 max-w-5xl mx-auto space-y-8 w-full overflow-hidden">
    
    <!-- Top Bar Navigation & Badges -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('simplification-service') }}" wire:navigate
            class="inline-flex items-center text-sm font-semibold text-neutral-500 hover:text-indigo-600 dark:text-neutral-400 dark:hover:text-indigo-400 transition-colors group">
            <svg class="size-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Materials List
        </a>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 text-xs font-bold rounded-full bg-purple-50 text-purple-700 dark:bg-purple-950/80 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800/80 shadow-xs uppercase tracking-wider">
                <span class="size-2 rounded-full bg-purple-500 animate-ping"></span>
                AI Quiz Customizer
            </span>
        </div>
    </div>

    <!-- Flash Error Notifications -->
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

    <!-- FULL-SCREEN LOADING OVERLAY DURING QUIZ GENERATION -->
    <div wire:loading wire:target="generateQuiz" class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-950/80 backdrop-blur-md p-4 transition-all">
        <div class="bg-gradient-to-br from-purple-950 via-slate-900 to-indigo-950 text-white border border-purple-500/30 rounded-3xl p-8 max-w-lg w-full shadow-2xl space-y-6 relative overflow-hidden text-center">
            <!-- Background Glow FX -->
            <div class="absolute -top-12 -right-12 size-40 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 size-40 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Central Animated AI Spinner -->
            <div class="relative mx-auto size-20 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-purple-500/20 border-t-purple-400 border-r-indigo-400 animate-spin"></div>
                <div class="p-4 bg-purple-600/30 rounded-full border border-purple-400/40 text-purple-300">
                    <svg class="size-8 animate-pulse text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
            </div>

            <!-- Title & Status Description -->
            <div class="space-y-2">
                <h3 class="text-xl font-extrabold tracking-tight text-white flex items-center justify-center gap-2">
                    Generating {{ $numQuestions }}-Question AI Quiz...
                </h3>
                <p class="text-xs text-purple-200 leading-relaxed max-w-sm mx-auto">
                    Gemini AI is analyzing your material, formulating multiple-choice questions, options, and explanations.
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="space-y-1.5">
                <div class="w-full bg-white/10 h-2.5 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-gradient-to-r from-purple-500 via-indigo-500 to-emerald-400 rounded-full animate-pulse w-4/5"></div>
                </div>
                <p class="text-[11px] text-purple-300 font-medium">Please wait a few seconds...</p>
            </div>
        </div>
    </div>

    <!-- Main Grid Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Selected Material Overview Card (1 Col on LG) -->
        <div class="bg-white dark:bg-neutral-900 rounded-3xl p-6 shadow-sm border border-neutral-200 dark:border-neutral-800 space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-neutral-100 dark:border-neutral-800">
                <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl shrink-0">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="truncate">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Source Material</span>
                    <h2 class="text-sm font-bold text-neutral-900 dark:text-neutral-100 truncate">{{ $material->title }}</h2>
                </div>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-950/50 rounded-xl">
                    <span class="text-neutral-500 dark:text-neutral-400">Original File</span>
                    <span class="font-bold text-neutral-800 dark:text-neutral-200 truncate max-w-[150px]">{{ $material->original_filename }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-950/50 rounded-xl">
                    <span class="text-neutral-500 dark:text-neutral-400">Format & Size</span>
                    <span class="font-bold text-neutral-800 dark:text-neutral-200 uppercase">{{ strtoupper($material->file_type) }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-950/50 rounded-xl">
                    <span class="text-neutral-500 dark:text-neutral-400">Simplified Word Count</span>
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $material->simplified_word_count ?? $material->original_word_count }} words</span>
                </div>
            </div>

            @if ($material->summary)
                <div class="p-4 bg-indigo-50/50 dark:bg-indigo-950/30 rounded-2xl border border-indigo-100 dark:border-indigo-900/40 space-y-1.5">
                    <h4 class="text-[11px] font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider">Executive Summary</h4>
                    <p class="text-xs text-indigo-950 dark:text-indigo-200 leading-relaxed line-clamp-4">
                        {{ $material->summary->summary }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Right: Quiz Generation Configurator Form (2 Cols on LG) -->
        <div class="lg:col-span-2 bg-white dark:bg-neutral-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200 dark:border-neutral-800 space-y-6">
            <div class="flex items-center gap-3.5 pb-4 border-b border-neutral-100 dark:border-neutral-800">
                <div class="p-3 bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-2xl shrink-0">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">Configure AI Quiz Generation</h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Choose how many questions you'd like Gemini AI to generate from this material</p>
                </div>
            </div>

            <form wire:submit="generateQuiz" class="space-y-6">
                <!-- Question Count Selector -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Number of Questions <span class="text-rose-500">*</span>
                    </label>

                    <!-- Quick Select Pills -->
                    <div class="grid grid-cols-5 gap-2.5">
                        @foreach ([3, 5, 8, 10, 15] as $count)
                            <button type="button" wire:click="$set('numQuestions', {{ $count }})"
                                class="py-3 px-2 rounded-2xl text-xs font-extrabold border transition-all text-center cursor-pointer {{ $numQuestions == $count ? 'bg-purple-600 text-white border-purple-600 shadow-md shadow-purple-200 dark:shadow-none' : 'bg-neutral-50 dark:bg-neutral-950 text-neutral-700 dark:text-neutral-300 border-neutral-200 dark:border-neutral-800 hover:border-purple-400' }}">
                                {{ $count }}
                                <span class="block text-[10px] font-medium opacity-80">Questions</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Custom Number Input Slider / Field -->
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

                <!-- Feature Highlights Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div class="p-3.5 bg-purple-50/60 dark:bg-purple-950/30 rounded-2xl border border-purple-100 dark:border-purple-900/40 flex items-start gap-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/60 text-purple-600 dark:text-purple-300 rounded-xl shrink-0">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-purple-950 dark:text-purple-200">Interactive Quiz Taker</h4>
                            <p class="text-[11px] text-purple-800/80 dark:text-purple-300/70">One question at a time with instant score calculation.</p>
                        </div>
                    </div>

                    <div class="p-3.5 bg-indigo-50/60 dark:bg-indigo-950/30 rounded-2xl border border-indigo-100 dark:border-indigo-900/40 flex items-start gap-3">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/60 text-indigo-600 dark:text-indigo-300 rounded-xl shrink-0">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-indigo-950 dark:text-indigo-200">Detailed Explanations</h4>
                            <p class="text-[11px] text-indigo-800/80 dark:text-indigo-300/70">Full feedback provided for wrong answers at the end of the quiz.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-purple-200 dark:shadow-none transition-all cursor-pointer">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        <span>Generate {{ $numQuestions }}-Question AI Quiz</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
