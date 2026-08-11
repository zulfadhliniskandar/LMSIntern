<div class="bg-white dark:bg-neutral-900 rounded-3xl p-6 sm:p-8 shadow-xs border border-neutral-200 dark:border-neutral-800 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-neutral-900 dark:text-white flex items-center gap-2">
                <svg class="size-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                My Quiz History & Performance
            </h2>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                View your generated quizzes, number of attempts, latest scores, and retake assessments anytime.
            </p>
        </div>
    </div>

    <div>
        {{ $this->table }}
    </div>
</div>

