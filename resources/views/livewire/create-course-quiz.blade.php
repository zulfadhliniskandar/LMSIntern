<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-zinc-800 pb-6">
        <div>
            <a href="{{ route('instructor-course-details', $subject->id) }}" wire:navigate
               class="inline-flex items-center text-xs font-semibold text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mb-3 group">
                <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to {{ $subject->name }}
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Create Course Quiz</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60">
                    {{ $subject->code }}
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Design and publish custom multiple-choice assessments for your students.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-zinc-800 text-xs font-medium text-slate-600 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700/60">
                <strong class="text-indigo-600 dark:text-indigo-400 font-bold">{{ count($questions) }}</strong> {{ count($questions) === 1 ? 'Question' : 'Questions' }}
            </span>
        </div>
    </div>

    <form wire:submit.prevent="saveQuiz(true)" class="space-y-8">
        <!-- Quiz Details & Schedule Card -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-zinc-800 p-6 space-y-6">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-zinc-800/80">
                <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Quiz Overview & Schedule</h2>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Set the title, instructions, and availability period.</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1.5">Quiz Title <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="title" placeholder="e.g., Chapter 1: Introduction to Data Structures" 
                           class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    @error('title') <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1.5">Description / Instructions</label>
                    <textarea wire:model="description" rows="3" placeholder="Provide general guidelines, time limits, or allowed materials for students..." 
                              class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-y"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Open Date & Time <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" wire:model="open_date" 
                               class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @error('open_date') <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Close Date & Time <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" wire:model="close_date" 
                               class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @error('close_date') <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1.5 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Time Limit (Mins)
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium lowercase">optional</span>
                        </label>
                        <input type="number" min="1" max="600" wire:model="time_limit" placeholder="e.g. 30 (Unlimited if blank)" 
                               class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @error('time_limit') <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1.5 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Allowed Attempts <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="number" min="1" max="100" wire:model="max_attempts" placeholder="e.g. 1" 
                               class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        @error('max_attempts') <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Builder Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Question Builder</h2>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Add questions, list options, and select the single correct answer.</p>
                </div>
            </div>

            @foreach($questions as $qIndex => $question)
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xs border border-slate-200/80 dark:border-zinc-800 p-6 space-y-5 transition-all">
                    <!-- Question Card Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800/80">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-lg bg-indigo-600 text-white text-xs font-bold">
                                Question #{{ sprintf('%02d', $qIndex + 1) }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">Multiple Choice</span>
                        </div>

                        @if(count($questions) > 1)
                            <button type="button" wire:click="removeQuestion({{ $qIndex }})" 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Question
                            </button>
                        @endif
                    </div>

                    <!-- Question Prompt Input -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300 mb-1.5">Question Text <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="questions.{{ $qIndex }}.question" 
                               placeholder="e.g. Which data structure operates on a First In First Out (FIFO) principle?" 
                               class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium">
                        @error("questions.{$qIndex}.question") <span class="text-xs font-medium text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Options Container -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-zinc-300">Answer Options & Correct Answer <span class="text-red-500">*</span></label>
                            <span class="text-[11px] text-slate-400">Click radio button to mark as correct</span>
                        </div>

                        @error("questions.{$qIndex}.options") 
                            <p class="text-xs font-medium text-red-500 bg-red-50 dark:bg-red-950/40 p-2 rounded-lg border border-red-200 dark:border-red-800/50">{{ $message }}</p> 
                        @enderror

                        <div class="grid grid-cols-1 gap-3">
                            @foreach($question['options'] as $oIndex => $option)
                                <div class="flex items-center gap-3 p-3 rounded-xl border transition-all {{ $option['is_correct'] ? 'bg-emerald-50/60 dark:bg-emerald-950/30 border-emerald-500 dark:border-emerald-700 shadow-xs' : 'bg-slate-50/30 dark:bg-zinc-800/30 border-slate-200/80 dark:border-zinc-700/70 hover:border-slate-300 dark:hover:border-zinc-600' }}">
                                    <!-- Custom Radio Option Selector -->
                                    <label class="flex items-center justify-center cursor-pointer shrink-0">
                                        <input type="radio" 
                                               name="correct_option_{{ $qIndex }}" 
                                               wire:click="setCorrectOption({{ $qIndex }}, {{ $oIndex }})" 
                                               {{ $option['is_correct'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                    </label>

                                    <!-- Option Letter Badge -->
                                    <span class="w-6 h-6 rounded-lg font-bold text-xs flex items-center justify-center shrink-0 {{ $option['is_correct'] ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-zinc-700 text-slate-600 dark:text-zinc-300' }}">
                                        {{ chr(65 + $oIndex) }}
                                    </span>

                                    <!-- Option Input -->
                                    <div class="flex-1">
                                        <input type="text" 
                                               wire:model="questions.{{ $qIndex }}.options.{{ $oIndex }}.option_text" 
                                               placeholder="Option answer text..." 
                                               class="w-full px-3 py-1.5 bg-transparent border-0 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-0 font-medium">
                                    </div>

                                    @if($option['is_correct'])
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/50 px-2.5 py-1 rounded-md">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Correct Answer
                                        </span>
                                    @endif

                                    @if(count($question['options']) > 2)
                                        <button type="button" wire:click="removeOption({{ $qIndex }}, {{ $oIndex }})" 
                                                class="p-1 text-slate-400 hover:text-red-500 transition-colors shrink-0"
                                                title="Delete Option">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                @error("questions.{$qIndex}.options.{$oIndex}.option_text") 
                                    <span class="text-xs font-medium text-red-500 pl-11 block">{{ $message }}</span> 
                                @enderror
                            @endforeach
                        </div>
                    </div>

                    <!-- Add Option Action -->
                    <div class="pt-1">
                        <button type="button" wire:click="addOption({{ $qIndex }})" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add Choice Option
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Add Question Card Button -->
            <button type="button" wire:click="addQuestion" 
                    class="w-full py-4 border-2 border-dashed border-indigo-200 dark:border-indigo-900/60 hover:border-indigo-400 dark:hover:border-indigo-600 rounded-2xl text-indigo-600 dark:text-indigo-400 font-bold hover:bg-indigo-50/50 dark:hover:bg-indigo-950/20 transition-all flex items-center justify-center gap-2 group shadow-xs">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Another Question</span>
            </button>
        </div>

        <!-- Floating Footer Action Bar -->
        <div class="sticky bottom-6 z-20 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-slate-200 dark:border-zinc-800 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-zinc-400">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Ready to save or publish for <strong>{{ $subject->name }}</strong></span>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" wire:click="saveQuiz(false)" 
                        class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-bold transition-all shadow-xs">
                    Save Draft
                </button>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-xs font-bold shadow-md transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Publish Quiz</span>
                </button>
            </div>
        </div>
    </form>
</div>
