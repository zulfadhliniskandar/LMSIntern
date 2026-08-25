<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Navigation & Timer Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-slate-200/80 dark:border-zinc-800 shadow-xs">
        <div>
            @if(auth()->user()?->isInstructor())
                <a href="{{ route('instructor-course-details', $subject->id) }}" wire:navigate
                   class="inline-flex items-center text-xs font-semibold text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mb-2">
            @else
                <a href="{{ route('student-course-details', $subject->id) }}" wire:navigate
                   class="inline-flex items-center text-xs font-semibold text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mb-2">
            @endif
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to {{ $subject->code }}
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $courseQuiz->title }}</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">{{ $subject->name }}</p>
        </div>

        @if(!$isSubmitted && $deadlineIso)
            <div x-data="quizTimer('{{ $deadlineIso }}')" x-init="initTimer()" 
                 class="flex items-center gap-2 px-4 py-2 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-xl shrink-0">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="text-[10px] uppercase font-bold text-amber-800 dark:text-amber-300 block">Time Remaining</span>
                    <span class="text-lg font-mono font-extrabold text-amber-900 dark:text-amber-200" x-text="timeLeft">--:--</span>
                </div>
            </div>
        @endif
    </div>

    @if(!$isSubmitted)
        <!-- Question Quick Jump Bar -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 border border-slate-200/80 dark:border-zinc-800 shadow-xs flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-500 dark:text-zinc-400 mr-2 uppercase tracking-wider">Questions:</span>
            @foreach($courseQuiz->questions->values() as $idx => $q)
                @php
                    $isAnswered = isset($userAnswers[$q->id]);
                    $isCurrent = $idx === $currentQuestionIndex;
                @endphp
                <button type="button" wire:click="goToQuestion({{ $idx }})"
                    class="w-8 h-8 rounded-xl font-bold text-xs flex items-center justify-center transition-all cursor-pointer 
                    {{ $isCurrent ? 'ring-2 ring-indigo-600 dark:ring-indigo-400 bg-indigo-600 text-white' : ($isAnswered ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800' : 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-300') }}">
                    {{ $idx + 1 }}
                </button>
            @endforeach
        </div>

        <!-- Main Question Card -->
        @if($currentQuestion)
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200/80 dark:border-zinc-800 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-zinc-800">
                    <span class="px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 text-xs font-bold">
                        Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Single Choice</span>
                </div>

                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white leading-snug">
                        {{ $currentQuestion->question }}
                    </h2>

                    <div class="space-y-3 pt-2">
                        @foreach($currentQuestion->options as $oIdx => $opt)
                            @php
                                $isSelected = isset($userAnswers[$currentQuestion->id]) && $userAnswers[$currentQuestion->id] == $opt->id;
                            @endphp
                            <div wire:click="selectOption({{ $currentQuestion->id }}, {{ $opt->id }})"
                                class="flex items-center gap-3.5 p-4 rounded-xl border transition-all cursor-pointer select-none 
                                {{ $isSelected ? 'bg-emerald-50/70 dark:bg-emerald-950/40 border-emerald-500 dark:border-emerald-600 shadow-sm' : 'bg-slate-50/50 dark:bg-zinc-800/40 border-slate-200/80 dark:border-zinc-700/80 hover:border-slate-300 dark:hover:border-zinc-600' }}">
                                <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 {{ $isSelected ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 dark:border-zinc-600' }}">
                                    @if($isSelected)
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="w-6 h-6 rounded-md text-xs font-bold flex items-center justify-center shrink-0 {{ $isSelected ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-zinc-300' }}">
                                    {{ chr(65 + $oIdx) }}
                                </span>
                                <span class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $opt->option_text }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Navigation Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-zinc-800">
                    <button type="button" wire:click="previousQuestion" {{ $currentQuestionIndex === 0 ? 'disabled' : '' }}
                        class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 text-xs font-bold disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors">
                        Previous
                    </button>

                    <div class="flex items-center gap-3">
                        @if($currentQuestionIndex < $totalQuestions - 1)
                            <button type="button" wire:click="nextQuestion"
                                class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-xs">
                                Next Question
                            </button>
                        @endif

                        <button type="button" wire:click="submitQuiz(false)" wire:confirm="Are you sure you want to submit your quiz answers?"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                            Submit Quiz
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Quiz Results Display -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 border border-slate-200/80 dark:border-zinc-800 shadow-sm text-center space-y-6">
            <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Quiz Completed!</h2>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Here is your assessment performance overview.</p>
            </div>

            <div class="max-w-xs mx-auto p-4 bg-slate-50 dark:bg-zinc-800/60 rounded-2xl border border-slate-200/60 dark:border-zinc-700">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Final Score</span>
                <span class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 block my-1">
                    {{ $score }} / {{ $totalQuestions }}
                </span>
                <span class="text-xs font-semibold text-slate-600 dark:text-zinc-300">
                    {{ round(($score / max(1, $totalQuestions)) * 100) }}% Accuracy
                </span>
            </div>

            <!-- Detailed Question Feedback -->
            <div class="text-left space-y-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Review Answers</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto pr-1">
                    @foreach($courseQuiz->questions as $qIdx => $question)
                        @php
                            $userAns = $userAnswers[$question->id] ?? null;
                            $correctOpt = $question->options->firstWhere('is_correct', true);
                            $isCorrect = $userAns && $correctOpt && $userAns == $correctOpt->id;
                        @endphp
                        <div class="p-4 rounded-xl border {{ $isCorrect ? 'bg-emerald-50/50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800/50' : 'bg-rose-50/50 dark:bg-rose-950/30 border-rose-200 dark:border-rose-800/50' }} space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 dark:text-white">#{{ $qIdx + 1 }}. {{ $question->question }}</span>
                                <span class="px-2 py-0.5 rounded font-bold uppercase text-[10px] {{ $isCorrect ? 'bg-emerald-200 text-emerald-900' : 'bg-rose-200 text-rose-900' }}">
                                    {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                </span>
                            </div>

                            <div class="space-y-1 pl-2 border-l-2 {{ $isCorrect ? 'border-emerald-400' : 'border-rose-400' }}">
                                @foreach($question->options as $opt)
                                    <div class="flex items-center justify-between {{ $opt->is_correct ? 'text-emerald-700 dark:text-emerald-300 font-bold' : ($userAns == $opt->id ? 'text-rose-700 dark:text-rose-300 font-medium' : 'text-slate-500') }}">
                                        <span>{{ $opt->option_text }}</span>
                                        @if($opt->is_correct)
                                            <span class="text-[10px] bg-emerald-100 dark:bg-emerald-900/60 px-1.5 py-0.5 rounded">Correct Answer</span>
                                        @elseif($userAns == $opt->id)
                                            <span class="text-[10px] bg-rose-100 dark:bg-rose-900/60 px-1.5 py-0.5 rounded">Your Selection</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($question->explanation)
                                <div class="p-2 bg-slate-100 dark:bg-zinc-800 rounded-lg text-slate-600 dark:text-zinc-300 text-[11px]">
                                    <strong>Explanation:</strong> {{ $question->explanation }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-zinc-800">
                @if(auth()->user()?->isInstructor())
                    <a href="{{ route('instructor-course-details', $subject->id) }}" wire:navigate
                       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-xs">
                @else
                    <a href="{{ route('student-course-details', $subject->id) }}" wire:navigate
                       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-xs">
                @endif
                    <span>Return to Course Details</span>
                </a>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
     let lastSwitchTime = 0;
     
     function handleVisibilityChange(){
        if(document.hidden){
            const now = Date.now();
            if(lastSwitchTime > 0 && now - lastSwitchTime > 2000){
                lastSwitchTime = now;

                @this.call('recordTabSwitch');
                alert("You switched tabs, this is a warning!");
            }
        }
     }

     document.addEventListener('visibilitychange', handleVisibilityChange);

     window.addEventListener('blur', () => {
        handleVisibilityChange();
     });
    });
    
    function quizTimer(deadlineIso) {
        return {
            deadline: deadlineIso ? new Date(deadlineIso).getTime() : null,
            timeLeft: '--:--',
            interval: null,
            initTimer() {
                if (!this.deadline) return;
                this.updateClock();
                this.interval = setInterval(() => {
                    this.updateClock();
                }, 1000);
            },
            updateClock() {
                const now = new Date().getTime();
                const diff = this.deadline - now;

                if (diff <= 0) {
                    this.timeLeft = '00:00';
                    clearInterval(this.interval);
                    @this.call('submitQuiz', true);
                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                
                const hours = Math.floor(minutes / 60);
                const remMinutes = minutes % 60;

                if (hours > 0) {
                    this.timeLeft = `${String(hours).padStart(2, '0')}:${String(remMinutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                } else {
                    this.timeLeft = `${String(remMinutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }
        }
    }
</script>
