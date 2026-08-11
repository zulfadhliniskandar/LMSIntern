<div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto space-y-8 w-full overflow-hidden" x-data="{ submitting: false, fileName: '', fileSize: '' }">
    
    <!-- Top Bar Navigation & Badges -->
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
                AI Learning Suite
            </span>
        </div>
    </div>

    <!-- FULL-SCREEN AI PROCESSING MODAL (Triggered during upload action) -->
    <div x-show="submitting" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-950/80 backdrop-blur-md p-4 transition-all">
        <div class="bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 text-white border border-indigo-500/30 rounded-3xl p-8 max-w-lg w-full shadow-2xl space-y-6 relative overflow-hidden text-center">
            <!-- Background Glow FX -->
            <div class="absolute -top-12 -right-12 size-40 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 size-40 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Central Animated AI Spinner -->
            <div class="relative mx-auto size-20 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-500/20 border-t-indigo-400 border-r-purple-400 animate-spin"></div>
                <div class="p-4 bg-indigo-600/30 rounded-full border border-indigo-400/40 text-indigo-300">
                    <svg class="size-8 animate-pulse text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <!-- Title & Status Description -->
            <div class="space-y-2">
                <h3 class="text-xl font-extrabold tracking-tight text-white flex items-center justify-center gap-2">
                    AI Simplification in Progress...
                </h3>
                <p class="text-xs text-indigo-200 leading-relaxed max-w-sm mx-auto">
                    Gemini AI is currently extracting text, simplifying complex vocabulary & sentence structures, and compiling key takeaways.
                </p>
            </div>

            <!-- Animated Step Timeline -->
            <div class="p-4 bg-white/5 rounded-2xl border border-white/10 text-left space-y-3 text-xs">
                <div class="flex items-center gap-3 text-emerald-400 font-semibold">
                    <div class="size-5 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>1. Document uploaded & text extracted</span>
                </div>
                <div class="flex items-center gap-3 text-indigo-300 font-semibold">
                    <div class="size-5 rounded-full bg-indigo-500/30 flex items-center justify-center shrink-0">
                        <svg class="animate-spin size-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <span>2. Gemini AI simplifying concepts...</span>
                </div>
                <div class="flex items-center gap-3 text-neutral-400">
                    <div class="size-5 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                        <span class="size-1.5 rounded-full bg-neutral-400"></span>
                    </div>
                    <span>3. Calculating readability & PDF report</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="space-y-1.5">
                <div class="w-full bg-white/10 h-2.5 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-400 rounded-full animate-pulse w-4/5"></div>
                </div>
                <p class="text-[11px] text-indigo-300 font-medium">Please do not refresh or close this page</p>
            </div>
        </div>
    </div>

    <!-- Flash Notifications -->
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
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
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
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Hero Banner Card -->
    <div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 space-y-3 max-w-3xl">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-white/10 text-indigo-200 border border-white/20 backdrop-blur-md">
                    ✨ AI Simplification & Quizzes
                </span>
                <span class="text-xs font-bold text-purple-200 bg-purple-900/40 px-2.5 py-1 rounded-full border border-purple-400/30">
                    PDF, DOCX, PPTX, TXT
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white leading-tight">
                Text Simplification & Assessment Service
            </h1>
            <p class="text-indigo-200 text-sm md:text-base leading-relaxed">
                Transform dense academic papers and lecture materials into simplified content, executive summaries, downloadable PDFs, and instant interactive AI quizzes.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none hidden sm:block">
            <svg class="size-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
        </div>
    </div>

    <!-- Active Processing Card (Polled when materials are pending/processing) -->
    @php
        $processingItems = $materials->filter(fn($m) => in_array($m->simplification_status, ['pending', 'processing']));
    @endphp

    @if ($processingItems->isNotEmpty())
        <div wire:poll.3s class="p-6 bg-gradient-to-r from-indigo-950 via-slate-900 to-purple-950 text-white rounded-3xl shadow-lg border border-indigo-500/30 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-indigo-500/20 text-indigo-300 rounded-2xl border border-indigo-400/30 shrink-0">
                        <svg class="animate-spin size-6 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            AI Simplification Processing Phase
                            <span class="px-2.5 py-0.5 text-[10px] uppercase tracking-wider font-extrabold bg-indigo-500/40 text-indigo-200 rounded-full border border-indigo-400/30">Active Loading Phase</span>
                        </h3>
                        <p class="text-xs text-indigo-200 mt-0.5">Please wait while Gemini AI extracts text, simplifies vocabulary, generates key takeaways, and compiles your PDF report.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 pt-2 border-t border-white/10">
                @foreach ($processingItems as $procMaterial)
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="font-bold text-sm text-white flex items-center gap-2">
                                <span>{{ $procMaterial->title }}</span>
                                <span class="text-xs font-normal text-indigo-300">({{ $procMaterial->original_filename }})</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-300">
                                <span class="flex items-center gap-1 text-emerald-400 font-semibold">
                                    <svg class="size-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    1. Document Text Extracted
                                </span>
                                <span class="flex items-center gap-1 text-indigo-300 font-semibold">
                                    <svg class="animate-spin size-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    2. Gemini AI Simplifying Concepts...
                                </span>
                                <span class="flex items-center gap-1 text-neutral-400">
                                    3. Readability & Summary Report
                                </span>
                            </div>
                        </div>

                        <div class="w-full md:w-56 bg-white/10 h-2 rounded-full overflow-hidden shrink-0">
                            <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-400 animate-pulse rounded-full w-3/4"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Responsive Grid: Upload Form (2 cols on lg) & How it Works (1 col on lg) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upload Form -->
        <div class="lg:col-span-2 bg-white dark:bg-neutral-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200 dark:border-neutral-800 space-y-6">
            <div class="flex items-center gap-3.5 pb-4 border-b border-neutral-100 dark:border-neutral-800">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-2xl shrink-0">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">Upload New Learning Material</h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Extract, simplify, and convert materials into AI study guides & quizzes</p>
                </div>
            </div>

            <form action="{{ route('simplification-service.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-5"
                  @submit="submitting = true">
                @csrf

                <!-- Title Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Material Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Chapter 4 - Cellular Respiration Notes"
                        class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" />
                    @error('title')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Description / Context <span class="text-neutral-400 font-normal text-xs">(Optional)</span>
                    </label>
                    <textarea name="description" rows="2" placeholder="Add study notes or context about this material..."
                        class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl text-sm text-neutral-900 dark:text-neutral-100 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload Dropzone -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                        Document File <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative border-2 border-dashed border-neutral-200 dark:border-neutral-800 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl p-6 sm:p-8 transition-all bg-neutral-50/50 dark:bg-neutral-950/40 text-center group cursor-pointer">
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.pptx,.ppt"
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
                            <p class="text-[11px] text-neutral-400 dark:text-neutral-500">Supported formats: PDF, DOCX, TXT, PPTX (Max 20MB)</p>
                        </div>
                    </div>

                    @error('file')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Selected File Banner Preview (Alpine-driven) -->
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

                <!-- Submit Button -->
                <div class="pt-2 flex justify-end">
                    <button type="submit" :disabled="submitting"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 disabled:opacity-50 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-200 dark:shadow-none transition-all cursor-pointer">
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>Upload & Process Material</span>
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Processing with AI...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>

        <!-- How It Works Sidebar Card -->
        <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 text-white rounded-3xl p-6 sm:p-8 shadow-md relative overflow-hidden flex flex-col justify-between space-y-6">
            <div class="space-y-4 relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-md">
                    <svg class="size-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>How AI Workflow Works</span>
                </div>

                <div class="space-y-3.5 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">1</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Upload Material</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">Upload your PDF, Word document, or PPT slide notes.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">2</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Text Extraction</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">Parser extracts raw text and evaluates Flesch reading ease scores.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">3</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">AI Simplification</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">Gemini AI rewrites jargon into clear language and bulleted takeaways.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="size-6 rounded-full bg-indigo-500/30 text-indigo-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 border border-indigo-400/30">4</div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Generate AI Quiz</h4>
                            <p class="text-[11px] text-indigo-200 leading-relaxed">One-click interactive multiple-choice quiz with explanations.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 relative z-10 flex items-center justify-between text-xs text-indigo-300 font-medium">
                <span>Fast & Reliable</span>
                <span>Powered by Gemini AI</span>
            </div>
        </div>
    </div>

    <!-- My Materials Section (Filament Table) -->
    <div class="space-y-4 pt-2 w-full">
        <div class="flex items-center justify-between pb-2 border-b border-neutral-200 dark:border-neutral-800">
            <h2 class="text-lg sm:text-xl font-bold text-neutral-900 dark:text-neutral-100 flex items-center gap-2.5">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-xl">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                My Simplified Materials
            </h2>
        </div>

        {{ $this->table }}
    </div>

    <!-- Preview Modal -->
    <flux:modal name="preview-material-modal" wire:model="showPreviewModal" class="max-w-4xl">
        @if ($selectedMaterial)
            <div class="space-y-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-neutral-100 dark:border-neutral-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-xl">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <flux:heading size="lg" class="font-bold">{{ $selectedMaterial->title }}</flux:heading>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Original File: {{ $selectedMaterial->original_filename }}</p>
                        </div>
                    </div>
                </div>

                <!-- Readability Metrics Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 space-y-2">
                        <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider block">Original Document Metrics</span>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-neutral-600 dark:text-neutral-400">Word Count:</span>
                            <span class="font-bold text-neutral-900 dark:text-white">{{ $selectedMaterial->original_word_count }} words</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-neutral-600 dark:text-neutral-400">Reading Level:</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">
                                {{ $selectedMaterial->original_reading_level ?? 'Advanced' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-neutral-600 dark:text-neutral-400">Readability Score:</span>
                            <span class="font-bold text-neutral-900 dark:text-white">{{ $selectedMaterial->original_readability_score ?? '45.0' }} / 100</span>
                        </div>
                    </div>

                    <div class="p-4 bg-emerald-50/60 dark:bg-emerald-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/50 space-y-2">
                        <span class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider block">Simplified Document Metrics</span>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-neutral-600 dark:text-neutral-400">Word Count:</span>
                            <span class="font-bold text-emerald-700 dark:text-emerald-300">{{ $selectedMaterial->simplified_word_count }} words</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-neutral-600 dark:text-neutral-400">Reading Level:</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                {{ $selectedMaterial->simplified_reading_level ?? 'High School' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-neutral-600 dark:text-neutral-400">Readability Score:</span>
                            <span class="font-bold text-emerald-700 dark:text-emerald-300">{{ $selectedMaterial->simplified_readability_score ?? '75.0' }} / 100</span>
                        </div>
                    </div>
                </div>

                <!-- Summary & Key Points -->
                @if ($selectedMaterial->summary)
                    <div class="space-y-4 pt-2">
                        <div class="p-4 bg-indigo-50/60 dark:bg-indigo-950/30 rounded-2xl border border-indigo-100 dark:border-indigo-900/50 space-y-1.5">
                            <h4 class="text-xs font-bold text-indigo-900 dark:text-indigo-300 uppercase tracking-wider">Executive Summary</h4>
                            <p class="text-xs text-indigo-950 dark:text-indigo-200 leading-relaxed">
                                {{ $selectedMaterial->summary->summary }}
                            </p>
                        </div>

                        @if (!empty($selectedMaterial->summary->key_points))
                            <div class="space-y-2">
                                <h4 class="text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">Key Concepts & Takeaways</h4>
                                <ul class="space-y-1.5 text-xs text-neutral-700 dark:text-neutral-300">
                                    @php
                                        $points = is_array($selectedMaterial->summary->key_points)
                                            ? $selectedMaterial->summary->key_points
                                            : json_decode($selectedMaterial->summary->key_points, true);
                                    @endphp
                                    @if (is_array($points))
                                        @foreach ($points as $point)
                                            <li class="flex items-start gap-2">
                                                <svg class="size-4 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>{{ $point }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Simplified Text Content Preview -->
                <div class="space-y-2 pt-2 border-t border-neutral-100 dark:border-neutral-800">
                    <h4 class="text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">Simplified Text Preview</h4>
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 max-h-72 overflow-y-auto text-xs text-neutral-800 dark:text-neutral-200 leading-relaxed prose dark:prose-invert prose-xs max-w-none prose-indigo prose-ul:list-disc prose-ul:pl-4 prose-li:my-0.5 prose-h3:text-indigo-600 dark:prose-h3:text-indigo-400 prose-blockquote:border-l-4 prose-blockquote:border-indigo-500 prose-blockquote:bg-indigo-50/50 dark:prose-blockquote:bg-indigo-950/30 prose-blockquote:px-3 prose-blockquote:py-1 prose-blockquote:rounded-r-lg">
                        {!! \Illuminate\Support\Str::markdown($selectedMaterial->simplified_text ?? '') !!}
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-neutral-100 dark:border-neutral-800">
                    <flux:modal.close>
                        <flux:button variant="filled">Close</flux:button>
                    </flux:modal.close>
                    <button wire:click="downloadPdf({{ $selectedMaterial->id }})"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download PDF Report</span>
                    </button>
                </div>
            </div>
        @endif
    </flux:modal>
</div>