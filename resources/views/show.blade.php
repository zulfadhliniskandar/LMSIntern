<x-layouts::app :title="$quiz->title">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header & Breadcrumbs -->
        <div class="flex items-center justify-between">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm font-medium text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200 transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Learning Materials
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                <svg class="size-3.5 animate-spin-slow text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                </svg>
                AI Generated Assessment
            </span>
        </div>

        <!-- Quiz Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-950 p-6 md:p-8 text-white shadow-xl">
            <div class="relative z-10 space-y-2">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                    {{ $quiz->title }}
                </h1>
                <p class="text-sm md:text-base text-indigo-200 max-w-2xl">
                    Test your understanding of the uploaded material with interactive questions and instant AI explanations.
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-2 text-xs font-medium text-indigo-200/80">
                    <div class="flex items-center gap-1.5">
                        <svg class="size-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $quiz->total_questions }} Questions</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="size-4 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Self-Paced</span>
                    </div>
                </div>
            </div>
            <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
                <svg class="size-72 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            </div>
        </div>

        <!-- Livewire Quiz Taker Component -->
        <livewire:quiz-taker :quiz="$quiz" />
    </div>
</x-layouts::app>