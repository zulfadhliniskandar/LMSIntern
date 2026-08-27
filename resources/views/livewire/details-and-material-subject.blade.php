<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Top Bar with Back Button -->
    <div class="flex items-center justify-between">
        @if (auth()->user()?->isInstructor())
            <a href="{{ route('instructor-course') }}" wire:navigate
                class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
        @elseif (auth()->user()?->isStudent())
                <a href="{{ route('taking-course') }}" wire:navigate
                    class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            @endif
                <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Courses
            </a>

            @if (auth()->user()?->isInstructor())
                <div class="flex items-center gap-2">
                    <a href="{{ route('instructor-course-addMaterial', $subject->id) }}" wire:navigate
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Add Material</span>
                    </a>

                    <a href="{{ route('instructor-course-assignment', $subject->id) }}" wire:navigate
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Add Assignment</span>
                    </a>

                    <a href="{{ route('instructor-course-quiz-create', $subject->id) }}" wire:navigate
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Create Quiz</span>
                    </a>
                </div>
            @endif
    </div>

    <!-- Flash Alert -->
    @if (session()->has('success'))
        <div
            class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Subject Header Banner -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 -mt-4 -mr-4 w-48 h-48 bg-gradient-to-br from-indigo-500/10 via-purple-500/10 to-transparent rounded-full blur-2xl pointer-events-none">
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                        {{ $subject->code }}
                    </span>
                    @if($subject->credit_hours)
                        <span
                            class="text-xs font-medium text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                            {{ $subject->credit_hours }} Credit Hours
                        </span>
                    @endif
                    <span
                        class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-2.5 py-1 rounded-full border border-indigo-100 dark:border-indigo-900">
                        {{ count($materials) }} Materials
                    </span>
                    <span
                        class="text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/50 px-2.5 py-1 rounded-full border border-purple-100 dark:border-purple-900">
                        {{ count($assignment) }} Assignments
                    </span>
                    <span
                        class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-2.5 py-1 rounded-full border border-emerald-100 dark:border-emerald-900">
                        {{ count($quizzes) }} Quizzes
                    </span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">
                    {{ $subject->name }}
                </h1>
                <p class="text-slate-600 dark:text-slate-300 max-w-3xl leading-relaxed text-sm">
                    {{ $subject->description }}
                </p>
            </div>
        </div>
    </div>

    <!-- Course Materials Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                Course Materials
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">({{ count($materials) }})</span>
            </h2>
            @if (auth()->user()?->isInstructor())
                <a href="{{ route('instructor-course-addMaterial', $subject->id) }}" wire:navigate
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-xs font-semibold transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Material</span>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($materials as $material)
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-xs border border-gray-100 dark:border-gray-700 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-slate-900 dark:text-white text-base">
                                {{ $material->title }}
                            </h3>
                            @if (auth()->user()?->isInstructor())
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button wire:click="openModal({{ $material->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-xs font-semibold transition-colors cursor-pointer"
                                        title="Edit Material">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                    <button wire:confirm="Are you sure you want to delete this material?"
                                        wire:click="deleteMaterial({{ $material->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-semibold transition-colors cursor-pointer"
                                        title="Delete Material">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if($material->description)
                            <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2">
                                {{ $material->description }}
                            </p>
                        @endif

                        @if($material->instruction)
                            <div
                                class="p-2.5 bg-slate-50 dark:bg-gray-900/50 rounded-xl text-xs text-slate-600 dark:text-slate-400 border border-slate-100 dark:border-gray-700/50">
                                <strong
                                    class="text-slate-700 dark:text-slate-300 font-semibold block mb-0.5">Instruction:</strong>
                                {{ $material->instruction }}
                            </div>
                        @endif
                    </div>

                    @if(is_array($material->file_path))
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 space-y-2">
                            {{-- Single File --}}
                            @if(isset($material->file_path['file']) && is_array($material->file_path['file']))
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($material->file_path['file']['path']) }}"
                                    target="_blank" download
                                    class="flex items-center gap-2 p-2 bg-slate-50 dark:bg-gray-900/50 hover:bg-indigo-50 dark:hover:bg-gray-900/80 rounded-xl text-xs font-medium text-indigo-600 dark:text-indigo-400 border border-slate-100 dark:border-gray-700/50 transition-colors group">
                                    <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span
                                        class="truncate group-hover:underline">{{ $material->file_path['file']['original_name'] ?? 'Download File' }}</span>
                                </a>
                            @elseif(!empty($material->file_path['files']) && is_array($material->file_path['files']))
                                <div class="space-y-1.5">
                                    <span
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Attached
                                        Files ({{ count($material->file_path['files']) }})</span>
                                    @foreach($material->file_path['files'] as $fileItem)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($fileItem['path']) }}" target="_blank"
                                            download
                                            class="flex items-center gap-2 p-2 bg-slate-50 dark:bg-gray-900/50 hover:bg-indigo-50 dark:hover:bg-gray-900/80 rounded-xl text-xs font-medium text-indigo-600 dark:text-indigo-400 border border-slate-100 dark:border-gray-700/50 transition-colors group">
                                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            <span
                                                class="truncate group-hover:underline">{{ $fileItem['original_name'] ?? 'Download File' }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @elseif(isset($material->file_path['path']))
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($material->file_path['path']) }}"
                                    target="_blank" download
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    {{ $material->file_path['original_name'] ?? 'Download Attachment' }}
                                </a>
                            @endif

                            {{-- Multiple Links --}}
                            @if(!empty($material->file_path['links']) && is_array($material->file_path['links']))
                                <div class="space-y-1.5 pt-1">
                                    <span
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Resource
                                        Links ({{ count($material->file_path['links']) }})</span>
                                    @foreach($material->file_path['links'] as $linkItem)
                                        <a href="{{ $linkItem['url'] }}" target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-2 p-2 bg-slate-50 dark:bg-gray-900/50 hover:bg-sky-50 dark:hover:bg-gray-900/80 rounded-xl text-xs font-medium text-sky-600 dark:text-sky-400 border border-slate-100 dark:border-gray-700/50 transition-colors group">
                                            <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            <span
                                                class="truncate group-hover:underline">{{ $linkItem['title'] ?? $linkItem['url'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @elseif(isset($material->file_path['url']))
                                <a href="{{ $material->file_path['url'] }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Open Resource Link
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div
                    class="col-span-full flex flex-col items-center justify-center p-10 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                    <svg class="w-10 h-10 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1.001 1.001 0 01.707.293l5.414 5.414a1.001 1.001 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-slate-600 dark:text-slate-400 font-medium text-sm">No materials uploaded yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Course Assignments & Quizzes Section -->
    <div class="space-y-4 pt-4">
        <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <div class="p-2 bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                Assignments
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">({{ count($assignment) }})</span>
            </h2>
            @if (auth()->user()?->isInstructor())
                <a href="{{ route('instructor-course-assignment', $subject->id) }}" wire:navigate
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/60 text-purple-600 dark:text-purple-400 text-xs font-semibold transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Assignment</span>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($assignment as $item)
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-xs border border-purple-100 dark:border-gray-700 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 mb-1.5 uppercase tracking-wide">
                                    Assignment
                                </span>
                                <a href="{{ route('course-assignment-details', [$subject->id, $item->id]) }}" wire:navigate
                                    class="hover:underline block">
                                    <h3 class="font-bold text-slate-900 dark:text-white text-base">
                                        {{ $item->title }}
                                    </h3>
                                    @if(auth()->user()?->isStudent())
                                        @php
                                            $hasSubmitted = $item->submissions ? $item->submissions->where('student_id', auth()->user()->student?->student_id)->isNotEmpty() : false;
                                        @endphp
                                        @if ($hasSubmitted)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 mb-1.5 uppercase tracking-wide">
                                                Submitted
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 mb-1.5 uppercase tracking-wide">
                                                Not Submitted
                                            </span>
                                        @endif
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 mb-1.5 uppercase tracking-wide">
                                            {{ $item->submissions ? $item->submissions->count() : 0 }} Submissions
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ route('course-assignment-details', [$subject->id, $item->id]) }}" wire:navigate
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/60 text-purple-600 dark:text-purple-400 text-xs font-semibold transition-colors cursor-pointer"
                                    title="View Details">
                                    <span>View</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                @if (auth()->user()?->isInstructor())
                                    <button wire:click="openAssignmentModal({{ $item->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-xs font-semibold transition-colors cursor-pointer shrink-0"
                                        title="Edit Assignment">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                    <button wire:confirm="Are you sure you want to delete this assignment?"
                                        wire:click="deleteAssignment({{ $item->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-semibold transition-colors cursor-pointer shrink-0"
                                        title="Delete Assignment">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($item->due_date)
                            <div
                                class="flex items-center gap-2 p-2 bg-amber-50 dark:bg-amber-950/30 rounded-xl border border-amber-200/60 dark:border-amber-800/40 text-xs">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <span class="text-amber-800 dark:text-amber-300 font-bold block">Due Date</span>
                                    <span class="text-amber-700 dark:text-amber-400 font-medium">
                                        {{ \Carbon\Carbon::parse($item->due_date)->format('F d, Y - h:i A') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($item->description)
                            <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2">
                                {{ $item->description }}
                            </p>
                        @endif
                    </div>

                    @if(is_array($item->file_path))
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 space-y-2">
                            {{-- Files --}}
                            @if(!empty($item->file_path['files']) && is_array($item->file_path['files']))
                                <div class="space-y-1.5">
                                    <span
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Assignment
                                        Files ({{ count($item->file_path['files']) }})</span>
                                    @foreach($item->file_path['files'] as $fileItem)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($fileItem['path']) }}" target="_blank"
                                            download
                                            class="flex items-center gap-2 p-2 bg-purple-50/50 dark:bg-gray-900/50 hover:bg-purple-100 dark:hover:bg-gray-900/80 rounded-xl text-xs font-medium text-purple-700 dark:text-purple-300 border border-purple-100 dark:border-gray-700/50 transition-colors group">
                                            <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <span
                                                class="truncate group-hover:underline">{{ $fileItem['original_name'] ?? 'Download File' }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Links --}}
                            @if(!empty($item->file_path['links']) && is_array($item->file_path['links']))
                                <div class="space-y-1.5 pt-1">
                                    <span
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Reference
                                        Links ({{ count($item->file_path['links']) }})</span>
                                    @foreach($item->file_path['links'] as $linkItem)
                                        <a href="{{ $linkItem['url'] }}" target="_blank" rel="noopener noreferrer"
                                            class="flex items-center gap-2 p-2 bg-sky-50/50 dark:bg-gray-900/50 hover:bg-sky-100 dark:hover:bg-gray-900/80 rounded-xl text-xs font-medium text-sky-700 dark:text-sky-300 border border-sky-100 dark:border-gray-700/50 transition-colors group">
                                            <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            <span
                                                class="truncate group-hover:underline">{{ $linkItem['title'] ?? $linkItem['url'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div
                    class="col-span-full flex flex-col items-center justify-center p-10 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                    <svg class="w-10 h-10 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-slate-600 dark:text-slate-400 font-medium text-sm">No assignments added yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Course Quizzes Section -->
    <div class="space-y-4 pt-4">
        <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <div
                    class="p-2 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                Quizzes
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">({{ count($quizzes) }})</span>
            </h2>
            @if (auth()->user()?->isInstructor())
                <a href="{{ route('instructor-course-quiz-create', $subject->id) }}" wire:navigate
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 text-xs font-semibold transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Create Quiz</span>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($quizzes as $quiz)
                @php
                    $now = \Carbon\Carbon::now();
                    $openDate = $quiz->open_date ? \Carbon\Carbon::parse($quiz->open_date) : null;
                    $closeDate = $quiz->close_date ? \Carbon\Carbon::parse($quiz->close_date) : null;

                    $isNotOpenYet = $openDate && $now->lt($openDate);
                    $isClosed = $closeDate && $now->gt($closeDate);
                    $isOpen = $quiz->isPublished && (!$openDate || $now->gte($openDate)) && (!$closeDate || $now->lte($closeDate));

                    $userAttempts = $quiz->attempts ? $quiz->attempts->where('user_id', auth()->id()) : collect();
                    $completedAttempts = $userAttempts->whereNotNull('completed_at');
                    $inProgressAttempt = $userAttempts->whereNull('completed_at')->first();

                    $maxAttempts = $quiz->max_attempts ?? 1;
                    $completedCount = $completedAttempts->count();
                    $attemptsLeft = max(0, $maxAttempts - $completedCount);

                    $latestAttempt = $completedAttempts->sortByDesc('completed_at')->first();
                @endphp
                <div
                    class="bg-white dark:bg-zinc-900 rounded-2xl p-5 shadow-xs border border-slate-200/80 dark:border-zinc-800 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden space-y-4">
                    <div class="space-y-3">
                        <!-- Header Status Badges -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 uppercase tracking-wide">
                                    Quiz
                                </span>
                                @if(auth()->user()?->isInstructor())
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $quiz->isPublished ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300' }}">
                                        {{ $quiz->isPublished ? 'Published' : 'Draft' }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $quiz->total_questions }} Qs
                                </span>
                                @if($quiz->time_limit)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900">
                                        {{ $quiz->time_limit }} Mins
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400">
                                        No Limit
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Quiz Title & Description -->
                        <div>
                            <h3 class="font-extrabold text-slate-900 dark:text-white text-base tracking-tight leading-snug">
                                {{ $quiz->title }}
                            </h3>
                            @if($quiz->description)
                                <p class="text-xs text-slate-500 dark:text-zinc-400 line-clamp-2 mt-1">
                                    {{ $quiz->description }}
                                </p>
                            @endif
                        </div>

                        <!-- Schedule & Limit Information -->
                        <div
                            class="space-y-1.5 text-xs bg-slate-50 dark:bg-zinc-800/50 p-3 rounded-xl border border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between text-slate-600 dark:text-zinc-400">
                                <span class="font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Open:
                                </span>
                                <span class="font-semibold text-slate-800 dark:text-zinc-200">
                                    {{ $openDate ? $openDate->format('M d, Y - h:i A') : 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600 dark:text-zinc-400">
                                <span class="font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Due Date:
                                </span>
                                <span class="font-semibold text-amber-700 dark:text-amber-400">
                                    {{ $closeDate ? $closeDate->format('M d, Y - h:i A') : 'N/A' }}
                                </span>
                            </div>
                            <div
                                class="flex items-center justify-between text-slate-600 dark:text-zinc-400 pt-1.5 border-t border-slate-200/60 dark:border-zinc-700/60">
                                <span class="font-medium">Allowed Attempts:</span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $maxAttempts }} {{ $maxAttempts === 1 ? 'Attempt' : 'Attempts' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Instructor Action Bar -->
                    @if (auth()->user()?->isInstructor())
                        <div
                            class="pt-3 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between gap-2 flex-wrap">
                            <a href="{{ route('instructor-course-quiz-details', ['subject' => $subject->id, 'quiz' => $quiz->id]) }}"
                                wire:navigate
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 text-xs font-bold transition-colors cursor-pointer"
                                title="View Quiz Details & Submissions">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Results & Stats</span>
                            </a>

                            <div class="flex items-center gap-1 shrink-0">
                                <button wire:click="togglePublishQuiz({{ $quiz->id }})"
                                    class="inline-flex items-center px-2 py-1.5 rounded-xl text-xs font-bold {{ $quiz->isPublished ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300' }} transition-colors cursor-pointer"
                                    title="{{ $quiz->isPublished ? 'Unpublish Quiz' : 'Publish Quiz' }}">
                                    <span>{{ $quiz->isPublished ? 'Unpublish' : 'Publish' }}</span>
                                </button>

                                <button wire:click="openQuizModal({{ $quiz->id }})"
                                    class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-slate-600 dark:text-zinc-300 text-xs font-semibold transition-colors cursor-pointer"
                                    title="Edit Quiz Settings">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <button wire:confirm="Are you sure you want to delete this quiz?"
                                    wire:click="deleteQuiz({{ $quiz->id }})"
                                    class="p-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-semibold transition-colors cursor-pointer"
                                    title="Delete Quiz">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Student Status & Actions -->
                    @if(auth()->user()?->isStudent())
                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700 space-y-2">
                            @if($latestAttempt)
                                <div
                                    class="flex items-center justify-between text-xs p-2 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-200/60 dark:border-emerald-800/40">
                                    <span class="text-emerald-800 dark:text-emerald-300 font-semibold">Latest Score:</span>
                                    <span class="font-bold text-emerald-700 dark:text-emerald-300">
                                        {{ $latestAttempt->score }} / {{ $latestAttempt->total_questions }}
                                        ({{ round(($latestAttempt->score / max(1, $latestAttempt->total_questions)) * 100) }}%)
                                    </span>
                                </div>
                            @endif

                            @if($inProgressAttempt)
                                <a href="{{ route('student-take-course-quiz', [$subject->id, $quiz->id]) }}" wire:navigate
                                    class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Continue In-Progress Quiz</span>
                                </a>
                            @elseif($isOpen && $attemptsLeft > 0)
                                <div class="space-y-1.5">
                                    <div
                                        class="flex items-center justify-between text-[11px] font-semibold text-slate-500 dark:text-slate-400 px-1">
                                        <span>Remaining:</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $attemptsLeft }} of
                                            {{ $maxAttempts }} attempts left</span>
                                    </div>
                                    <a href="{{ route('student-take-course-quiz', [$subject->id, $quiz->id]) }}" wire:navigate
                                        class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        </svg>
                                        <span>Start Quiz</span>
                                    </a>
                                </div>
                            @elseif($isNotOpenYet)
                                <div class="p-2.5 bg-slate-100 dark:bg-gray-700/60 rounded-xl text-center">
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 block">Quiz Not Open
                                        Yet</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Opens
                                        {{ $openDate ? $openDate->format('M d, h:i A') : '' }}</span>
                                </div>
                            @elseif($isClosed)
                                <div
                                    class="p-2.5 bg-rose-50 dark:bg-rose-950/40 rounded-xl text-center border border-rose-100 dark:border-rose-900/50">
                                    <span class="text-xs font-bold text-rose-700 dark:text-rose-400 block">Quiz Closed (Due Date
                                        Passed)</span>
                                </div>
                            @elseif($attemptsLeft <= 0)
                                <div
                                    class="p-2.5 bg-amber-50 dark:bg-amber-950/40 rounded-xl text-center border border-amber-100 dark:border-amber-900/50">
                                    <span class="text-xs font-bold text-amber-700 dark:text-amber-400 block">Max Attempts Reached
                                        ({{ $completedCount }}/{{ $maxAttempts }})</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div
                    class="col-span-full flex flex-col items-center justify-center p-10 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                    <svg class="w-10 h-10 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-slate-600 dark:text-slate-400 font-medium text-sm">No quizzes published yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Edit Course Material Modal -->
    <flux:modal name="edit-material-modal" wire:model="showEditModal" class="max-w-xl">
        @if ($selectedMaterial)
            <form wire:submit.prevent="updateMaterial" class="space-y-5">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <flux:heading size="lg" class="font-bold">Edit Course Material</flux:heading>
                            <p class="text-xs text-slate-500 dark:text-zinc-400">Update title, instructions, files, or
                                resource links</p>
                        </div>
                    </div>
                </div>

                <!-- Title Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Material Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="editTitle" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" />
                    @error('editTitle')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Description <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                    </label>
                    <textarea wire:model="editDescription" rows="2"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none"></textarea>
                </div>

                <!-- Instruction Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Instructions for Students <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                    </label>
                    <textarea wire:model="editInstruction" rows="2"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-none"></textarea>
                </div>

                <!-- Existing Attached Files -->
                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-zinc-800">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Current Attached Files ({{ count($editFiles) }})
                    </label>
                    @if(!empty($editFiles))
                        <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                            @foreach($editFiles as $index => $fileItem)
                                <div
                                    class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 text-xs">
                                    <div class="flex items-center gap-2 truncate pr-2">
                                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span
                                            class="font-medium text-slate-700 dark:text-zinc-300 truncate">{{ $fileItem['original_name'] ?? 'Attached File' }}</span>
                                    </div>
                                    <button type="button" wire:click="removeExistingFile({{ $index }})"
                                        class="text-slate-400 hover:text-rose-500 transition-colors p-1" title="Remove File">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 dark:text-zinc-500 italic">No existing files attached.</p>
                    @endif
                </div>

                <!-- Upload Additional Files -->
                <div class="space-y-2 pt-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Upload Additional Files
                    </label>
                    <div
                        class="relative border-2 border-dashed border-slate-200 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-xl p-4 transition-colors bg-slate-50/50 dark:bg-zinc-900/40 text-center">
                        <input type="file" wire:model="newFiles" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div
                            class="flex items-center justify-center gap-2 pointer-events-none text-xs text-slate-600 dark:text-zinc-400 font-medium">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Click to add more files</span>
                        </div>
                    </div>

                    <!-- New Uploads Preview -->
                    @if(!empty($accumulatedNewFiles))
                        <div class="space-y-1.5 pt-1">
                            <span
                                class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block">New
                                Files to Add ({{ count($accumulatedNewFiles) }})</span>
                            <div class="space-y-1 max-h-32 overflow-y-auto">
                                @foreach($accumulatedNewFiles as $index => $newFile)
                                    <div
                                        class="flex items-center justify-between p-2 bg-indigo-50/60 dark:bg-indigo-950/40 rounded-lg border border-indigo-100 dark:border-indigo-900/50 text-xs">
                                        <span
                                            class="font-medium text-indigo-900 dark:text-indigo-200 truncate pr-2">{{ $newFile->getClientOriginalName() }}</span>
                                        <button type="button" wire:click="removeNewFile({{ $index }})"
                                            class="text-indigo-400 hover:text-rose-500 transition-colors p-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- External Links -->
                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Resource Links
                        </label>
                        <button type="button" wire:click="addEditLink"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Link
                        </button>
                    </div>

                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach($editLinks as $index => $link)
                            <div
                                class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 flex-grow">
                                    <input type="text" wire:model="editLinks.{{ $index }}.title"
                                        placeholder="Link Title (e.g. Video Lecture)"
                                        class="px-3 py-1.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-lg text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                                    <input type="url" wire:model="editLinks.{{ $index }}.url" placeholder="https://..."
                                        class="px-3 py-1.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-lg text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                                </div>
                                @if(count($editLinks) > 1)
                                    <button type="button" wire:click="removeEditLink({{ $index }})"
                                        class="text-slate-400 hover:text-rose-500 transition-colors p-1" title="Remove Link">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-zinc-800">
                    <flux:modal.close>
                        <flux:button variant="filled">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" icon="check" class="font-semibold">
                        Save Changes
                    </flux:button>
                </div>
            </form>
        @endif
    </flux:modal>

    <!-- Edit Assignment Modal -->
    <flux:modal name="edit-assignment-modal" wire:model="showEditAssignmentModal" class="max-w-xl">
        @if ($selectedAssignment)
            <form wire:submit.prevent="updateAssignment" class="space-y-5">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <flux:heading size="lg" class="font-bold">Edit Assignment</flux:heading>
                            <p class="text-xs text-slate-500 dark:text-zinc-400">Update title, description, due date, files,
                                or reference links</p>
                        </div>
                    </div>
                </div>

                <!-- Title Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Assignment Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="editAssignmentTitle" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                    @error('editAssignmentTitle')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Description / Instructions <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                    </label>
                    <textarea wire:model="editAssignmentDescription" rows="3"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all resize-none"></textarea>
                </div>

                <!-- Due Date & Time Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Due Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" wire:model="editAssignmentDate" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        @error('editAssignmentDate')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Due Time <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" wire:model="editAssignmentTime" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        @error('editAssignmentTime')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Existing Attached Files -->
                <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-zinc-800">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Current Attached Files ({{ count($editAssignmentFiles) }})
                    </label>
                    @if(!empty($editAssignmentFiles))
                        <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                            @foreach($editAssignmentFiles as $index => $fileItem)
                                <div
                                    class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 text-xs">
                                    <div class="flex items-center gap-2 truncate pr-2">
                                        <svg class="w-4 h-4 text-purple-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span
                                            class="font-medium text-slate-700 dark:text-zinc-300 truncate">{{ $fileItem['original_name'] ?? 'Attached File' }}</span>
                                    </div>
                                    <button type="button" wire:click="removeExistingAssignmentFile({{ $index }})"
                                        class="text-slate-400 hover:text-rose-500 transition-colors p-1" title="Remove File">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 dark:text-zinc-500 italic">No existing files attached.</p>
                    @endif
                </div>

                <!-- Upload Additional Files -->
                <div class="space-y-2 pt-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Upload Additional Files
                    </label>
                    <div
                        class="relative border-2 border-dashed border-slate-200 dark:border-zinc-700 hover:border-purple-500 dark:hover:border-purple-400 rounded-xl p-4 transition-colors bg-slate-50/50 dark:bg-zinc-900/40 text-center">
                        <input type="file" wire:model="newAssignmentFiles" multiple
                            accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div
                            class="flex items-center justify-center gap-2 pointer-events-none text-xs text-slate-600 dark:text-zinc-400 font-medium">
                            <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Click to add more assignment files</span>
                        </div>
                    </div>

                    <!-- New Uploads Preview -->
                    @if(!empty($accumulatedNewAssignmentFiles))
                        <div class="space-y-1.5 pt-1">
                            <span
                                class="text-[11px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider block">New
                                Files to Add ({{ count($accumulatedNewAssignmentFiles) }})</span>
                            <div class="space-y-1 max-h-32 overflow-y-auto">
                                @foreach($accumulatedNewAssignmentFiles as $index => $newFile)
                                    <div
                                        class="flex items-center justify-between p-2 bg-purple-50/60 dark:bg-purple-950/40 rounded-lg border border-purple-100 dark:border-purple-900/50 text-xs">
                                        <span
                                            class="font-medium text-purple-900 dark:text-purple-200 truncate pr-2">{{ $newFile->getClientOriginalName() }}</span>
                                        <button type="button" wire:click="removeNewAssignmentFile({{ $index }})"
                                            class="text-purple-400 hover:text-rose-500 transition-colors p-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- External Links -->
                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Reference Links
                        </label>
                        <button type="button" wire:click="addEditAssignmentLink"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-purple-600 dark:text-purple-400 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Link
                        </button>
                    </div>

                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach($editAssignmentLinks as $index => $link)
                            <div
                                class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 flex-grow">
                                    <input type="text" wire:model="editAssignmentLinks.{{ $index }}.title"
                                        placeholder="Link Title (e.g. Reference Specs)"
                                        class="px-3 py-1.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-lg text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-purple-500" />
                                    <input type="url" wire:model="editAssignmentLinks.{{ $index }}.url"
                                        placeholder="https://..."
                                        class="px-3 py-1.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-lg text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-purple-500" />
                                </div>
                                @if(count($editAssignmentLinks) > 1)
                                    <button type="button" wire:click="removeEditAssignmentLink({{ $index }})"
                                        class="text-slate-400 hover:text-rose-500 transition-colors p-1" title="Remove Link">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-zinc-800">
                    <flux:modal.close>
                        <flux:button variant="filled">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" icon="check" class="font-semibold">
                        Save Changes
                    </flux:button>
                </div>
            </form>
        @endif
    </flux:modal>

    <!-- Edit Course Quiz Modal -->
    <flux:modal name="edit-quiz-modal" wire:model="showEditQuizModal" class="max-w-xl">
        @if ($selectedQuiz)
            <form wire:submit.prevent="updateQuiz" class="space-y-5">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <div
                            class="p-2 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-xl">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <flux:heading size="lg" class="font-bold">Edit Quiz Settings</flux:heading>
                            <p class="text-xs text-slate-500 dark:text-zinc-400">Update title, schedule, time limit, or
                                attempt count</p>
                        </div>
                    </div>
                </div>

                <!-- Title Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Quiz Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="editQuizTitle" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" />
                    @error('editQuizTitle')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                        Description / Instructions <span class="text-slate-400 font-normal text-xs">(Optional)</span>
                    </label>
                    <textarea wire:model="editQuizDescription" rows="2"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all resize-none"></textarea>
                </div>

                <!-- Open & Close Dates -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Open Date & Time <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" wire:model="editQuizOpenDate" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" />
                        @error('editQuizOpenDate')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Due Date & Time <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" wire:model="editQuizCloseDate" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" />
                        @error('editQuizCloseDate')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Time Limit & Max Attempts -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Time Limit (Minutes)
                        </label>
                        <input type="number" min="1" max="600" wire:model="editQuizTimeLimit"
                            placeholder="Blank for no limit"
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" />
                        @error('editQuizTimeLimit')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                            Allowed Attempts <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" min="1" max="100" wire:model="editQuizMaxAttempts" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" />
                        @error('editQuizMaxAttempts')
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Published Checkbox -->
                <div class="flex items-center gap-2 pt-2">
                    <label
                        class="flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-zinc-300 cursor-pointer">
                        <input type="checkbox" wire:model="editQuizIsPublished"
                            class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500" />
                        <span>Publish Quiz for Students</span>
                    </label>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-zinc-800">
                    <flux:modal.close>
                        <flux:button variant="filled">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" icon="check" class="font-semibold">
                        Save Changes
                    </flux:button>
                </div>
            </form>
        @endif
    </flux:modal>

    <!-- View Quiz Results Modal -->
    <flux:modal name="quiz-results-modal" wire:model="showQuizResultsModal" class="max-w-3xl">
        @if ($selectedQuizForResults)
            <div class="space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800">
                    <div>
                        <flux:heading size="lg" class="font-bold">Student Results: {{ $selectedQuizForResults->title }}
                        </flux:heading>
                        <p class="text-xs text-slate-500 dark:text-zinc-400">Total Attempts Recorded:
                            {{ $selectedQuizForResults->attempts->count() }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr
                                class="border-b border-slate-200 dark:border-zinc-700 uppercase font-bold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-zinc-800/50">
                                <th class="py-2.5 px-3">Student</th>
                                <th class="py-2.5 px-3">Started</th>
                                <th class="py-2.5 px-3">Completed</th>
                                <th class="py-2.5 px-3">Integrity / Tab Switches</th>
                                <th class="py-2.5 px-3 text-right">Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse($selectedQuizForResults->attempts as $att)
                                <tr>
                                    <td class="py-2.5 px-3 font-semibold text-slate-900 dark:text-white">
                                        {{ $att->user->name ?? 'User #' . $att->user_id }}
                                        <span
                                            class="block text-[11px] font-normal text-slate-500">{{ $att->user->email ?? '' }}</span>
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-600 dark:text-slate-400">
                                        {{ $att->started_at ? $att->started_at->format('M d, Y - h:i A') : 'N/A' }}
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-600 dark:text-slate-400">
                                        @if($att->completed_at)
                                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                                {{ $att->completed_at->format('M d, Y - h:i A') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-bold">
                                                In Progress
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if(($att->tab_switch_count ?? 0) > 0)
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 font-bold text-[11px]"
                                                title="Recorded {{ $att->tab_switch_count }} tab/window switch events">
                                                <svg class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                {{ $att->tab_switch_count }}
                                                {{ $att->tab_switch_count === 1 ? 'Switch' : 'Switches' }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold text-[11px]">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Clean Attempt
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-bold text-slate-900 dark:text-white">
                                        @if($att->completed_at)
                                            {{ $att->score }} / {{ $att->total_questions }}
                                            <span
                                                class="text-slate-500 font-normal">({{ round(($att->score / max(1, $att->total_questions)) * 100) }}%)</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 italic">No student attempts recorded
                                        yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end pt-3 border-t border-slate-100 dark:border-zinc-800">
                    <flux:modal.close>
                        <flux:button variant="filled">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
    <div>
        <!-- Community Chat Sidebar (Lazy Loaded to maximize LCP performance) -->
        <livewire:course-chat :subjectId="$subject->id" lazy />
    </div>
</div>