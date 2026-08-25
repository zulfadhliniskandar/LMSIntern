<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Navigation Header -->
    <div
        class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-zinc-800 pb-6">
        <div>
            <a href="{{ route('instructor-course-details', $subject->id) }}" wire:navigate
                class="inline-flex items-center text-xs font-semibold text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mb-3 group">
                <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to {{ $subject->code }} Details
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $quiz->title }}
                </h1>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $quiz->isPublished ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800' }}">
                    {{ $quiz->isPublished ? 'Published' : 'Draft' }}
                </span>
            </div>
            @if($quiz->description)
                <p class="text-sm text-slate-600 dark:text-zinc-400 mt-2">{{ $quiz->description }}</p>
            @endif
            <div class="flex flex-wrap items-center gap-2 mt-3">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                    📚 {{ $subject->name }} ({{ $subject->code }})
                </span>
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                    ❓ {{ $quiz->total_questions }} Questions
                </span>
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                    ⏱️ {{ $quiz->time_limit ? $quiz->time_limit . ' Mins Time Limit' : 'No Time Limit' }}
                </span>
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                    🔄 {{ $quiz->max_attempts ?? 1 }}
                    {{ ($quiz->max_attempts ?? 1) === 1 ? 'Attempt Allowed' : 'Attempts Allowed' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Enrolled Students -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-200/80 dark:border-zinc-800 shadow-xs flex items-center gap-4">
            <div
                class="p-3 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Enrolled
                    Students</p>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white mt-0.5">{{ $enrolledStudentsCount }}
                </h3>
            </div>
        </div>

        <!-- Completed Attempts -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-200/80 dark:border-zinc-800 shadow-xs flex items-center gap-4">
            <div
                class="p-3 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Completed
                    Attempts</p>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white mt-0.5">
                    {{ $completedAttemptsCount }} <span class="text-xs font-normal text-slate-500">/
                        {{ $enrolledStudentsCount }}</span>
                </h3>
            </div>
        </div>

        <!-- Average Score -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-200/80 dark:border-zinc-800 shadow-xs flex items-center gap-4">
            <div class="p-3 bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Average Score
                </p>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white mt-0.5">{{ $avgScorePct }}%</h3>
            </div>
        </div>

        <!-- Flagged Attempts -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-200/80 dark:border-zinc-800 shadow-xs flex items-center gap-4">
            <div
                class="p-3 {{ $flaggedAttemptsCount > 0 ? 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400' : 'bg-slate-50 dark:bg-zinc-800 text-slate-500' }} rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Flagged
                    Attempts</p>
                <h3
                    class="text-2xl font-extrabold {{ $flaggedAttemptsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white' }} mt-0.5">
                    {{ $flaggedAttemptsCount }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Filament Table Card -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200/80 dark:border-zinc-800 shadow-xs overflow-hidden p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Student Submissions & Integrity Status</h2>
                <p class="text-xs text-slate-500 dark:text-zinc-400">View individual student scores, completion
                    timestamps, and anti-cheating tab switch records.</p>
            </div>
            <a href="{{ route('instructor-course-quiz-pdf', ['subject' => $subject->id, 'quiz' => $quiz->id]) }}"
                target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export PDF Report</span>
            </a>

        </div>

        {{ $this->table }}
    </div>

    <!-- Student Attempt Breakdown Modal -->
    <flux:modal name="student-attempt-details-modal" wire:model="showAttemptModal" class="max-w-3xl">
        @if($selectedAttemptForModal)
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-zinc-800">
                    <div>
                        <flux:heading size="lg" class="font-bold">Attempt Details:
                            {{ $selectedAttemptForModal->user->name ?? 'Student' }}</flux:heading>
                        <p class="text-xs text-slate-500 dark:text-zinc-400">
                            {{ $selectedAttemptForModal->user->email ?? '' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-extrabold text-slate-900 dark:text-white">
                            {{ $selectedAttemptForModal->score }} / {{ $selectedAttemptForModal->total_questions }}
                        </span>
                        <span class="block text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                            ({{ round(($selectedAttemptForModal->score / max(1, $selectedAttemptForModal->total_questions)) * 100) }}%)
                        </span>
                    </div>
                </div>

                <!-- Timestamps & Integrity Info -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 bg-slate-50 dark:bg-zinc-800/60 rounded-xl border border-slate-200/60 dark:border-zinc-700/60 text-xs">
                    <div>
                        <span class="text-slate-500 dark:text-zinc-400 block font-medium">Started At:</span>
                        <span class="font-bold text-slate-800 dark:text-zinc-200">
                            {{ $selectedAttemptForModal->started_at ? $selectedAttemptForModal->started_at->format('M d, Y - h:i A') : 'N/A' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-zinc-400 block font-medium">Submitted At:</span>
                        <span class="font-bold text-slate-800 dark:text-zinc-200">
                            {{ $selectedAttemptForModal->completed_at ? $selectedAttemptForModal->completed_at->format('M d, Y - h:i A') : 'In Progress' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-zinc-400 block font-medium">Integrity Record:</span>
                        @if(($selectedAttemptForModal->tab_switch_count ?? 0) > 0)
                            <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-bold">
                                ⚠️ {{ $selectedAttemptForModal->tab_switch_count }} Tab Switches
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                                ✅ Clean Attempt
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Tab Switch Violation Log Timeline -->
                @if(!empty($selectedAttemptForModal->violation_logs))
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase text-slate-500 dark:text-zinc-400 tracking-wider">Violation Log
                            Timeline</h4>
                        <div
                            class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-xl max-h-36 overflow-y-auto space-y-1 text-xs">
                            @foreach($selectedAttemptForModal->violation_logs as $vLog)
                                <div class="flex items-center justify-between text-rose-700 dark:text-rose-300">
                                    <span class="font-medium">⚠️ Tab switch / Window focus lost detected</span>
                                    <span class="text-[11px] opacity-80">
                                        {{ isset($vLog['timestamp']) ? \Carbon\Carbon::parse($vLog['timestamp'])->format('h:i:s A') : 'N/A' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Question & Answer Breakdown -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold uppercase text-slate-500 dark:text-zinc-400 tracking-wider">Answers
                        Breakdown</h4>
                    @foreach($selectedAttemptForModal->quiz->questions as $index => $question)
                        @php
                            $userAnswerId = $selectedAttemptForModal->answers[$question->id] ?? null;
                            $correctOption = $question->options->firstWhere('is_correct', true);
                            $userOption = $question->options->firstWhere('id', $userAnswerId);
                            $isCorrect = $userOption && $userOption->is_correct;
                        @endphp
                        <div
                            class="p-4 rounded-xl border {{ $isCorrect ? 'border-emerald-200 bg-emerald-50/40 dark:border-emerald-900/60 dark:bg-emerald-950/20' : 'border-rose-200 bg-rose-50/40 dark:border-rose-900/60 dark:bg-rose-950/20' }} space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <h5 class="text-sm font-bold text-slate-900 dark:text-white">
                                    Q{{ $index + 1 }}. {{ $question->question }}
                                </h5>
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold shrink-0 {{ $isCorrect ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300' }}">
                                    {{ $isCorrect ? 'Correct (+1)' : 'Incorrect (0)' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 text-xs">
                                @foreach($question->options as $opt)
                                    @php
                                        $isSelected = $userAnswerId == $opt->id;
                                        $isOptCorrect = $opt->is_correct;
                                    @endphp
                                    <div
                                        class="p-2.5 rounded-lg border flex items-center justify-between {{ $isSelected ? ($isOptCorrect ? 'border-emerald-500 bg-emerald-100/60 dark:bg-emerald-900/40 font-bold' : 'border-rose-500 bg-rose-100/60 dark:bg-rose-900/40 font-bold') : ($isOptCorrect ? 'border-emerald-300 bg-emerald-50 dark:bg-emerald-950/30' : 'border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/80') }}">
                                        <span class="text-slate-800 dark:text-zinc-200">{{ $opt->option_text }}</span>
                                        @if($isSelected)
                                            <span
                                                class="text-[10px] font-extrabold px-1.5 py-0.5 rounded {{ $isOptCorrect ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                                Student Selected
                                            </span>
                                        @elseif($isOptCorrect)
                                            <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded bg-emerald-600 text-white">
                                                Correct Answer
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-zinc-800">
                    <flux:button wire:click="closeAttemptModal" variant="filled">Close</flux:button>
                </div>
            </div>
        @endif
    </flux:modal>
</div>