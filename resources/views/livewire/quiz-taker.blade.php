<div class="space-y-6">

    @if(!$isSubmitted)
        {{-- ==================== PHASE 1: ACTIVE QUIZ STEPPER ==================== --}}

        <!-- Progress Header & Tracker -->
        <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">
                <span class="inline-flex items-center gap-1.5 text-indigo-600 dark:text-indigo-400">
                    <span class="flex size-2 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-ping"></span>
                    Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}
                </span>
                <span>{{ round((($currentQuestionIndex + 1) / $totalQuestions) * 100) }}% Complete</span>
            </div>
            
            <div class="w-full bg-neutral-100 dark:bg-neutral-800 h-3 rounded-full overflow-hidden p-0.5 border border-neutral-200/50 dark:border-neutral-700/50">
                <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-violet-600 h-full rounded-full transition-all duration-500 ease-out shadow-sm"
                    style="width: {{ (($currentQuestionIndex + 1) / $totalQuestions) * 100 }}%"></div>
            </div>
        </div>

        <!-- Question & Answers Card -->
        <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-6 md:p-8 shadow-sm space-y-6">
            
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-neutral-400 dark:text-neutral-500">
                    Question #{{ $currentQuestionIndex + 1 }}
                </span>
                <h2 class="text-xl md:text-2xl font-bold text-neutral-900 dark:text-neutral-100 leading-snug">
                    {{ $currentQuestion->question_text }}
                </h2>
            </div>

            <!-- Options Grid -->
            <div class="space-y-3 pt-2">
                @foreach($currentQuestion->options as $index => $option)
                    @php
                        $isSelected = ($userAnswers[$currentQuestion->id] ?? null) == $option->id;
                        $letter = chr(65 + $index); // A, B, C, D
                    @endphp

                    <button 
                        type="button" 
                        wire:click="selectOption({{ $currentQuestion->id }}, {{ $option->id }})"
                        class="group relative w-full text-left p-4 md:p-5 rounded-xl border-2 transition-all duration-200 flex items-center justify-between gap-4 cursor-pointer
                            {{ $isSelected 
                                ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50/70 dark:bg-indigo-950/40 text-indigo-950 dark:text-indigo-100 font-semibold shadow-md shadow-indigo-100/50 dark:shadow-none ring-2 ring-indigo-500/20' 
                                : 'border-neutral-200 dark:border-neutral-800 hover:border-indigo-300 dark:hover:border-indigo-700 text-neutral-800 dark:text-neutral-200 bg-white dark:bg-neutral-900 hover:bg-neutral-50/60 dark:hover:bg-neutral-800/40' }}">
                        
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- Letter Badge -->
                            <span class="flex-shrink-0 flex items-center justify-center size-8 rounded-lg text-xs font-bold transition-colors
                                {{ $isSelected 
                                    ? 'bg-indigo-600 text-white shadow-sm' 
                                    : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900 group-hover:text-indigo-700 dark:group-hover:text-indigo-300' }}">
                                {{ $letter }}
                            </span>
                            <span class="text-sm md:text-base leading-relaxed">{{ $option->option_text }}</span>
                        </div>

                        <!-- Selection Indicator -->
                        <div class="flex-shrink-0 flex items-center justify-center size-6 rounded-full border-2 transition-colors
                            {{ $isSelected ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-neutral-300 dark:border-neutral-700' }}">
                            @if($isSelected)
                                <svg class="size-3.5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Navigation Action Footer -->
        <div class="flex items-center justify-between pt-2">
            <button 
                type="button"
                wire:click="previousQuestion" 
                @if($currentQuestionIndex === 0) disabled @endif
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-neutral-200 dark:border-neutral-800 text-sm font-semibold text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-900 hover:bg-neutral-50 dark:hover:bg-neutral-800 disabled:opacity-40 disabled:pointer-events-none transition-all shadow-sm">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Previous
            </button>

            @if($currentQuestionIndex < $totalQuestions - 1)
                <button 
                    type="button"
                    wire:click="nextQuestion" 
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-indigo-200 dark:shadow-none hover:shadow-lg">
                    Next
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            @else
                <button 
                    type="button"
                    wire:click="submitQuiz" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-7 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-200 dark:shadow-none hover:shadow-xl active:scale-98 cursor-pointer">
                    <span wire:loading.remove wire:target="submitQuiz" class="flex items-center gap-2">
                        Submit Quiz
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </span>
                    <span wire:loading wire:target="submitQuiz" class="flex items-center gap-2">
                        <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Evaluating...
                    </span>
                </button>
            @endif
        </div>

    @else
        {{-- ==================== PHASE 2: RESULTS & EXPLANATION REVIEW ==================== --}}
        
        @php
            $percentage = round(($score / $totalQuestions) * 100);
            $isHigh = $percentage >= 80;
            $isMed = $percentage >= 50 && $percentage < 80;
        @endphp

        <!-- Performance Summary Card -->
        <div class="relative overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-8 shadow-sm text-center space-y-6">
            
            <div class="inline-flex p-4 rounded-full 
                {{ $isHigh ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : ($isMed ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-950 dark:text-rose-400') }}">
                @if($isHigh)
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-6.75A1.125 1.125 0 019 15.375V18.75m9 0h-9m9-12l-4.5 4.5M12 6.75l-4.5 4.5M12 6.75V15" />
                    </svg>
                @elseif($isMed)
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                @else
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                @endif
            </div>

            <div class="space-y-1">
                <h2 class="text-2xl md:text-3xl font-extrabold text-neutral-900 dark:text-neutral-100">
                    @if($isHigh) Excellent Performance! 🌟 @elseif($isMed) Good Job! Keep Reviewing 👍 @else Needs Practice 📚 @endif
                </h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Review your results below to understand correct answers and detailed AI explanations.
                </p>
            </div>

            <div class="flex justify-center items-baseline gap-2">
                <span class="text-5xl font-black tracking-tight text-neutral-900 dark:text-neutral-100">{{ $score }}</span>
                <span class="text-xl font-bold text-neutral-400 dark:text-neutral-500">/ {{ $totalQuestions }}</span>
                <span class="ml-2 px-3 py-1 text-xs font-extrabold rounded-full 
                    {{ $isHigh ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : ($isMed ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300') }}">
                    {{ $percentage }}% Grade
                </span>
            </div>

            <div class="pt-2">
                <button 
                    wire:click="retake" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-indigo-200 dark:shadow-none cursor-pointer">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Retake Quiz
                </button>
            </div>
        </div>

        <!-- Filterable Question Review Section -->
        <div x-data="{ filter: 'all' }" class="space-y-4 pt-4">
            
            <!-- Tab Headers -->
            <div class="flex items-center justify-between border-b border-neutral-200 dark:border-neutral-800 pb-3">
                <h3 class="text-lg font-bold text-neutral-900 dark:text-neutral-100">Question Review</h3>
                <div class="flex items-center gap-1.5 p-1 bg-neutral-100 dark:bg-neutral-800 rounded-xl text-xs font-semibold">
                    <button 
                        type="button" 
                        @click="filter = 'all'" 
                        :class="filter === 'all' ? 'bg-white dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="px-3 py-1.5 rounded-lg transition-all">
                        All ({{ $totalQuestions }})
                    </button>
                    <button 
                        type="button" 
                        @click="filter = 'incorrect'" 
                        :class="filter === 'incorrect' ? 'bg-white dark:bg-neutral-900 text-rose-600 dark:text-rose-400 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="px-3 py-1.5 rounded-lg transition-all">
                        Incorrect ({{ $totalQuestions - $score }})
                    </button>
                    <button 
                        type="button" 
                        @click="filter = 'correct'" 
                        :class="filter === 'correct' ? 'bg-white dark:bg-neutral-900 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'"
                        class="px-3 py-1.5 rounded-lg transition-all">
                        Correct ({{ $score }})
                    </button>
                </div>
            </div>

            <!-- Question Cards Loop -->
            <div class="space-y-5">
                @foreach($quiz->questions as $index => $question)
                    @php
                        $userChoiceId = $userAnswers[$question->id] ?? null;
                        $correctOption = $question->options->firstWhere('is_correct', true);
                        $isCorrect = ($userChoiceId == $correctOption?->id);
                    @endphp

                    <div 
                        x-show="filter === 'all' || (filter === 'correct' && {{ $isCorrect ? 'true' : 'false' }}) || (filter === 'incorrect' && {{ !$isCorrect ? 'true' : 'false' }})"
                        x-transition
                        class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-6 shadow-sm space-y-4">
                        
                        <div class="flex items-start justify-between gap-4">
                            <h4 class="text-base font-bold text-neutral-900 dark:text-neutral-100 leading-snug">
                                {{ $index + 1 }}. {{ $question->question_text }}
                            </h4>

                            @if($isCorrect)
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 text-xs font-bold rounded-full border border-emerald-200 dark:border-emerald-800">
                                    <svg class="size-3.5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Correct
                                </span>
                            @else
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-3 py-1 bg-rose-50 text-rose-700 dark:bg-rose-950/80 dark:text-rose-300 text-xs font-bold rounded-full border border-rose-200 dark:border-rose-800">
                                    <svg class="size-3.5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Incorrect
                                </span>
                            @endif
                        </div>

                        <!-- Options Choices Visual Breakdown -->
                        <div class="space-y-2">
                            @foreach($question->options as $optIndex => $option)
                                @php
                                    $isOptionCorrect = $option->is_correct;
                                    $isOptionSelected = ($userChoiceId == $option->id);
                                    $letter = chr(65 + $optIndex);
                                @endphp

                                <div class="p-3.5 rounded-xl border text-sm flex items-center justify-between gap-3 transition-colors
                                    @if($isOptionCorrect)
                                        bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-400 dark:border-emerald-600 text-emerald-950 dark:text-emerald-100 font-medium
                                    @elseif($isOptionSelected && !$isOptionCorrect)
                                        bg-rose-50/80 dark:bg-rose-950/40 border-rose-400 dark:border-rose-600 text-rose-950 dark:text-rose-100 font-medium
                                    @else
                                        border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 bg-neutral-50/50 dark:bg-neutral-900/50
                                    @endif">
                                    
                                    <div class="flex items-center gap-3">
                                        <span class="flex-shrink-0 flex items-center justify-center size-6 rounded-md text-xs font-bold
                                            @if($isOptionCorrect)
                                                bg-emerald-600 text-white
                                            @elseif($isOptionSelected && !$isOptionCorrect)
                                                bg-rose-600 text-white
                                            @else
                                                bg-neutral-200 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400
                                            @endif">
                                            {{ $letter }}
                                        </span>
                                        <span class="{{ $isOptionSelected && !$isOptionCorrect ? 'line-through opacity-80' : '' }}">{{ $option->option_text }}</span>
                                    </div>

                                    @if($isOptionCorrect)
                                        <span class="flex-shrink-0 inline-flex items-center gap-1 text-xs font-bold text-emerald-700 dark:text-emerald-300">
                                            <svg class="size-3.5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                            Correct Answer
                                        </span>
                                    @elseif($isOptionSelected)
                                        <span class="flex-shrink-0 inline-flex items-center gap-1 text-xs font-bold text-rose-700 dark:text-rose-300">
                                            Your Answer
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- AI Explanation Box -->
                        <div class="relative overflow-hidden rounded-xl border border-indigo-200/80 dark:border-indigo-800/80 bg-indigo-50/60 dark:bg-indigo-950/30 p-4.5 space-y-1">
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">
                                <svg class="size-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.516 0c.85.493 1.508 1.333 1.508 2.316V18" />
                                </svg>
                                AI Explanation
                            </div>
                            <p class="text-sm text-indigo-950 dark:text-indigo-200 leading-relaxed pl-6">
                                {{ $question->explanation }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>