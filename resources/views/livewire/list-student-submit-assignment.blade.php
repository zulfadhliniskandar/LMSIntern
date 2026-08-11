<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Top Bar with Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('course-assignment-details', [$subjectId ?? ($assignment->subjects_id ?? 1), $assignmentId ?? ($assignment->id ?? 1)]) }}"
            wire:navigate
            class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Assignment Details
        </a>
    </div>

    <!-- Header Banner Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 relative overflow-hidden space-y-2">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800 uppercase tracking-wider mb-2">
                    Done Submissions
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Students Who Have Submitted
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ $assignment->title ?? 'Assignment' }} • Course:
                    {{ $subject->name ?? ($assignment->subject?->name ?? 'Subject') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Filament Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700">
        {{ $this->table }}
    </div>
</div>