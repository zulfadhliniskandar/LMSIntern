<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Navigation & Header Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('student-submitted', [$subjectId ?? ($assignment->subjects_id ?? 1), $assignmentId ?? ($assignment->id ?? 1)]) }}"
            wire:navigate
            class="inline-flex items-center text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Submitted Students List
        </a>

        @if($submission)
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ $submission->status ?? 'Submitted' }}
            </span>
        @endif
    </div>

    @if(!$submission)
        <!-- Empty / Not Found State -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center shadow-xs border border-gray-100 dark:border-gray-700 space-y-3">
            <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Submission Not Found</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">The requested submission details could not be retrieved.</p>
        </div>
    @else
        @php
            $studentUser = $submission->student?->user;
            $studentName = $studentUser?->name ?? ('Student ' . $submission->student_id);
            $studentEmail = $studentUser?->email ?? 'N/A';
            $studentId = $submission->student_id;
            $programmeName = $submission->student?->programme?->name ?? 'N/A';

            $subFiles = is_array($submission->file_path)
                ? ($submission->file_path['files'] ?? (isset($submission->file_path[0]) ? $submission->file_path : []))
                : [];
        @endphp

        <!-- Student & Assignment Metadata Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-black text-xl uppercase shadow-md shadow-indigo-500/20 shrink-0">
                        {{ substr($studentName, 0, 2) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ $studentName }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6" />
                                </svg>
                                ID: {{ $studentId }}
                            </span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $studentEmail }}
                            </span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                </svg>
                                {{ $programmeName }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Submitted On</span>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                        {{ $submission->created_at ? $submission->created_at->format('F d, Y • h:i A') : 'N/A' }}
                    </span>
                </div>
            </div>

            <!-- Assignment Info Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 dark:bg-gray-900/50 p-4 rounded-xl border border-slate-200/60 dark:border-gray-700/60 text-xs">
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Assignment Title</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $assignment->title ?? 'Assignment' }}
                    </span>
                </div>
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider block text-[10px]">Course / Subject</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-sm mt-0.5 block">
                        {{ $subject->name ?? ($assignment->subject?->name ?? 'Subject') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Optional Text Submission Content -->
        @if(!empty($submission->submission_text))
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Student Submission Text / Notes
                </h3>
                <div class="p-4 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200/60 dark:border-gray-700/60 text-sm text-slate-800 dark:text-slate-200 whitespace-pre-line leading-relaxed">
                    {{ $submission->submission_text }}
                </div>
            </div>
        @endif

        <!-- Submitted Files List -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Submitted Files ({{ count($subFiles) }})
            </h3>

            @if(empty($subFiles))
                <div class="p-6 text-center bg-slate-50 dark:bg-gray-900/40 rounded-xl border border-dashed border-slate-200 dark:border-gray-700">
                    <p class="text-xs text-slate-500 dark:text-slate-400">No files uploaded in this submission.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($subFiles as $sFile)
                        @php
                            $sfPath = $sFile['path'] ?? $sFile['file_path'] ?? '';
                            $sfName = $sFile['original_name'] ?? $sFile['file_name'] ?? 'Submission File';
                        @endphp
                        @if($sfPath)
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200/80 dark:border-gray-700/80 hover:border-indigo-300 transition-all group">
                                <div class="flex items-center gap-3 truncate pr-2">
                                    <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-lg shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="truncate">
                                        <span class="text-xs font-bold text-slate-900 dark:text-white truncate block">
                                            {{ $sfName }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 block truncate">
                                            Path: {{ $sfPath }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($sfPath) }}" target="_blank" download
                                        class="p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-950/80 rounded-lg transition-colors"
                                        title="Download File">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Plagiarism & AI Detection Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Academic Integrity & AI Detection Reports
            </h3>

            <livewire:plagiarism-check-status :submissionId="$submission->id" :wire:key="'submission-details-plag-'.$submission->id" />
        </div>
    @endif
</div>