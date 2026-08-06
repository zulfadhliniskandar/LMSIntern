<div class="p-6 max-w-5xl mx-auto space-y-6">
    <!-- Top Navigation & Back Link -->
    <div class="flex items-center justify-between">
        @if (auth()->user()?->isInstructor())
            <a href="{{ route('instructor-course-details', $assignment->subjects_id ?? $this->assignment->subjects_id) }}"
                wire:navigate
                class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
        @else
                <a href="{{ route('student-course-details', $assignment->subjects_id ?? $this->assignment->subjects_id) }}"
                    wire:navigate
                    class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
            @endif
                <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to {{ $assignment->subject?->name ?? $this->assignment->subject?->name ?? 'Course Details' }}
            </a>

            @php
                $dueDateObj = ($assignment->due_date ?? $this->assignment->due_date) ? \Carbon\Carbon::parse($assignment->due_date ?? $this->assignment->due_date) : null;
                $isOverdue = $dueDateObj ? $dueDateObj->isPast() : false;
            @endphp

            <span
                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full {{ $isOverdue ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' }}">
                <span
                    class="w-2 h-2 rounded-full {{ $isOverdue ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                {{ $isOverdue ? 'Closed / Past Due' : 'Active Assignment' }}
            </span>
    </div>

    <!-- Flash Success Message -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 rounded-2xl text-emerald-800 dark:text-emerald-300 text-sm font-semibold shadow-xs">
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 rounded-xl shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 transition-colors p-1 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Assignment Main Hero Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 -mt-6 -mr-6 w-56 h-56 bg-gradient-to-br from-purple-500/10 via-indigo-500/10 to-transparent rounded-full blur-2xl pointer-events-none">
        </div>

        <div class="relative z-10 space-y-4">
            <!-- Header Badges -->
            <div class="flex flex-wrap items-center gap-2">
                @if($assignment->subject?->code ?? $this->assignment->subject?->code)
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                        {{ $assignment->subject?->code ?? $this->assignment->subject?->code }}
                    </span>
                @endif
                @if($assignment->subject?->name ?? $this->assignment->subject?->name)
                    <span
                        class="text-xs font-medium text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                        {{ $assignment->subject?->name ?? $this->assignment->subject?->name }}
                    </span>
                @endif
            </div>

            <!-- Assignment Title -->
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $assignment->title ?? $this->assignment->title }}
            </h1>

            <!-- Description / Instructions -->
            @if($assignment->description ?? $this->assignment->description)
                <div class="p-4 bg-slate-50 dark:bg-gray-900/50 rounded-xl border border-slate-100 dark:border-gray-700/50">
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">
                        Description / Instructions</h3>
                    <p class="text-slate-700 dark:text-slate-200 text-sm leading-relaxed whitespace-pre-line">
                        {{ $assignment->description ?? $this->assignment->description }}
                    </p>
                </div>
            @endif

            <!-- Key Info Cards (Due Date, End Time) -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-4 border-t border-gray-100 dark:border-gray-700/80">
                <!-- Due Date -->
                <div
                    class="p-4 bg-amber-50/70 dark:bg-amber-950/20 rounded-xl border border-amber-200/60 dark:border-amber-800/40 flex items-center gap-3">
                    <div
                        class="p-2.5 bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 rounded-lg shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <span
                            class="text-[11px] font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider block">Due
                            Date</span>
                        <span class="text-xs font-bold text-amber-950 dark:text-amber-100">
                            {{ ($assignment->due_date ?? $this->assignment->due_date) ? \Carbon\Carbon::parse($assignment->due_date ?? $this->assignment->due_date)->format('F d, Y') : 'No due date' }}
                        </span>
                    </div>
                </div>

                <!-- End Time -->
                <div
                    class="p-4 bg-purple-50/70 dark:bg-purple-950/20 rounded-xl border border-purple-200/60 dark:border-purple-800/40 flex items-center gap-3">
                    <div
                        class="p-2.5 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-lg shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span
                            class="text-[11px] font-bold text-purple-800 dark:text-purple-300 uppercase tracking-wider block">Due
                            Time</span>
                        <span class="text-xs font-bold text-purple-950 dark:text-purple-100">
                            @if($assignment->end_time ?? $this->assignment->end_time)
                                {{ \Carbon\Carbon::parse($assignment->end_time ?? $this->assignment->end_time)->format('h:i A') }}
                            @elseif($assignment->due_date ?? $this->assignment->due_date)
                                {{ \Carbon\Carbon::parse($assignment->due_date ?? $this->assignment->due_date)->format('h:i A') }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Status Card -->
                <div
                    class="p-4 bg-indigo-50/70 dark:bg-indigo-950/20 rounded-xl border border-indigo-200/60 dark:border-indigo-800/40 flex items-center gap-3">
                    <div
                        class="p-2.5 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-lg shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span
                            class="text-[11px] font-bold text-indigo-800 dark:text-indigo-300 uppercase tracking-wider block">Status</span>
                        <span class="text-xs font-bold text-indigo-950 dark:text-indigo-100">
                            {{ $isOverdue ? 'Submission Closed' : 'Accepting Submissions' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attachments & Links Container Card -->
    @php
        $filePath = $assignment->file_path ?? $this->assignment->file_path;
    @endphp

    @if(!empty($filePath))
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xs border border-gray-100 dark:border-gray-700 space-y-5">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Assignment Attachments & Links
            </h2>

            <div class="space-y-4">
                {{-- Handle array of files inside file_path['files'] --}}
                @if(is_array($filePath) && !empty($filePath['files']) && is_array($filePath['files']))
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                            Attached Files ({{ count($filePath['files']) }})
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($filePath['files'] as $fileItem)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($fileItem['path'] ?? $fileItem['file_path'] ?? '') }}"
                                    target="_blank" download
                                    class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-gray-900/60 hover:bg-indigo-50 dark:hover:bg-gray-900/90 rounded-xl border border-slate-200 dark:border-gray-700/60 transition-all group">
                                    <div class="flex items-center gap-3 truncate pr-2">
                                        <div
                                            class="p-2 bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-lg group-hover:scale-105 transition-transform shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <span
                                                class="text-xs font-bold text-slate-900 dark:text-white truncate block group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                {{ $fileItem['original_name'] ?? $fileItem['file_name'] ?? 'Attached File' }}
                                            </span>
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500 block">Click to
                                                download</span>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    {{-- Handle flat array of files --}}
                @elseif(is_array($filePath) && isset($filePath[0]) && is_array($filePath[0]))
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                            Attached Files ({{ count($filePath) }})
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($filePath as $fileItem)
                                @if(is_array($fileItem) && (isset($fileItem['file_path']) || isset($fileItem['path'])))
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($fileItem['file_path'] ?? $fileItem['path']) }}"
                                        target="_blank" download
                                        class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-gray-900/60 hover:bg-indigo-50 dark:hover:bg-gray-900/90 rounded-xl border border-slate-200 dark:border-gray-700/60 transition-all group">
                                        <div class="flex items-center gap-3 truncate pr-2">
                                            <div
                                                class="p-2 bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-lg group-hover:scale-105 transition-transform shrink-0">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="truncate">
                                                <span
                                                    class="text-xs font-bold text-slate-900 dark:text-white truncate block group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                    {{ $fileItem['file_name'] ?? $fileItem['original_name'] ?? 'Attached File' }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 dark:text-slate-500 block">Click to
                                                    download</span>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- External Links --}}
                @if(is_array($filePath) && !empty($filePath['links']) && is_array($filePath['links']))
                    <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-gray-700/80">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                            External Reference Links ({{ count($filePath['links']) }})
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($filePath['links'] as $linkItem)
                                <a href="{{ $linkItem['url'] }}" target="_blank" rel="noopener noreferrer"
                                    class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-gray-900/60 hover:bg-sky-50 dark:hover:bg-gray-900/90 rounded-xl border border-slate-200 dark:border-gray-700/60 transition-all group">
                                    <div class="flex items-center gap-3 truncate pr-2">
                                        <div
                                            class="p-2 bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 rounded-lg group-hover:scale-105 transition-transform shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <span
                                                class="text-xs font-bold text-slate-900 dark:text-white truncate block group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                                {{ $linkItem['title'] ?? $linkItem['url'] }}
                                            </span>
                                            <span
                                                class="text-[11px] text-slate-400 dark:text-slate-500 block truncate">{{ $linkItem['url'] }}</span>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-600 shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
    @if (auth()->user()?->isStudent())
        @if(!$this->hasSubmitted())
            <form wire:submit="submitAssignment">
                <div class="space-y-3 pt-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Upload Files <span class="text-slate-400 font-normal text-xs">(Select one or multiple files)</span>
                    </label>

                    <div
                        class="relative border-2 border-dashed border-slate-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl p-6 transition-colors bg-slate-50/50 dark:bg-gray-900/30 text-center">
                        <input type="file" wire:model="newFiles" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                        <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
                            <div
                                class="p-3 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-full">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                <span>Click to select files <span class="font-normal text-slate-500">or drag and
                                        drop</span></span>
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">PDF, DOCX, PPTX, ZIP up to 10MB each
                            </p>
                        </div>
                    </div>

                    <!-- Livewire Loading State -->
                    <div wire:loading wire:target="newFiles"
                        class="flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-400 font-medium">
                        <svg class="animate-spin h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962,0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Uploading selected files...
                    </div>

                    <!-- Selected Files List -->
                    @if(!empty($files))
                        <div class="space-y-2 pt-1">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Selected Files
                                ({{ count($files) }})</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($files as $index => $file)
                                    <div
                                        class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-gray-900/60 rounded-xl border border-slate-200 dark:border-gray-700/60 text-xs">
                                        <div class="flex items-center gap-2 truncate pr-2">
                                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <span
                                                class="font-medium text-slate-700 dark:text-slate-300 truncate">{{ $file->getClientOriginalName() }}</span>
                                        </div>
                                        <button type="button" wire:click="removeFile({{ $index }})"
                                            class="text-slate-400 hover:text-rose-500 transition-colors p-1" title="Remove File">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @error('files')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror
                    @error('newFiles.*')
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                    @enderror

                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Submit Assignment</span>
                        </button>
                    </div>
                </div>
            </form>

        @else
            @php
                $mySub = $submission ?? $this->submission;
            @endphp
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-gray-700 p-6 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-3 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Assignment Already Submitted</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Submitted on
                                {{ $mySub?->created_at ? \Carbon\Carbon::parse($mySub->created_at)->format('F d, Y - h:i A') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        {{ $mySub?->status ?? 'Submitted' }}
                    </span>
                </div>

                <!-- Display Submitted Files -->
                @if($mySub && !empty($mySub->file_path))
                    <div class="space-y-3">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                            Your Submitted Files
                        </span>

                        @php
                            $subFiles = is_array($mySub->file_path)
                                ? ($mySub->file_path['files'] ?? (isset($mySub->file_path[0]) ? $mySub->file_path : []))
                                : [];
                        @endphp

                        @if(!empty($subFiles))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($subFiles as $subFile)
                                    @php
                                        $fPath = $subFile['path'] ?? $subFile['file_path'] ?? '';
                                        $fName = $subFile['original_name'] ?? $subFile['file_name'] ?? 'Submitted File';
                                    @endphp
                                    @if($fPath)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($fPath) }}" target="_blank" download
                                            class="flex items-center justify-between p-3.5 bg-emerald-50/40 dark:bg-gray-900/60 hover:bg-emerald-100/60 dark:hover:bg-gray-900/90 rounded-xl border border-emerald-100 dark:border-gray-700/60 transition-all group">
                                            <div class="flex items-center gap-3 truncate pr-2">
                                                <div
                                                    class="p-2 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-lg group-hover:scale-105 transition-transform shrink-0">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="truncate">
                                                    <span
                                                        class="text-xs font-bold text-slate-900 dark:text-white truncate block group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                                        {{ $fName }}
                                                    </span>
                                                    <span class="text-[11px] text-slate-400 dark:text-slate-500 block">Click to download
                                                        submitted file</span>
                                                </div>
                                            </div>
                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="pt-4 flex justify-end">
                <button type="button"
                    wire:confirm="Are you sure you want to remove your submission and uploaded files?"
                    wire:click="removeSubmission"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-50 hover:bg-rose-100 active:bg-rose-200 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-xl border border-rose-200/80 dark:border-rose-800/60 shadow-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Remove Submission</span>
                </button>
            </div>
        @endif
    @endif
</div>