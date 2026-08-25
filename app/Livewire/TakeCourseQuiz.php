<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subjects;
use App\Models\CourseQuizzes;
use App\Models\CouseQuizAttempts;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class TakeCourseQuiz extends Component
{
    public Subjects $subject;
    public CourseQuizzes $courseQuiz;
    public ?CouseQuizAttempts $attempt = null;

    public int $currentQuestionIndex = 0;
    public array $userAnswers = [];
    public bool $isSubmitted = false;
    public int $score = 0;
    public int $totalQuestions = 0;
    public ?string $deadlineIso = null;

    public function mount(Subjects $subject, CourseQuizzes $courseQuiz)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if ($courseQuiz->subject_id !== $subject->id) {
            abort(404);
        }

        $this->subject = $subject;
        $this->courseQuiz = $courseQuiz->load(['questions.options']);
        $this->totalQuestions = $this->courseQuiz->questions->count();

        $now = Carbon::now();

        // Check if quiz is published for students
        if (!$user->isInstructor() && !$courseQuiz->isPublished) {
            Notification::make()
                ->title("Quiz not available")
                ->body("This quiz is currently unavailable.")
                ->danger()
                ->send();
            return redirect()->route('student-course-details', $subject->id);
        }

        // Check open date
        if ($courseQuiz->open_date && $now->lt(Carbon::parse($courseQuiz->open_date))) {
            Notification::make()
                ->title("Quiz not open yet")
                ->body("This quiz will open on " . Carbon::parse($courseQuiz->open_date)->format('M d, Y - h:i A'))
                ->warning()
                ->send();
            return redirect()->route('student-course-details', $subject->id);
        }

        // Check user attempts
        $userAttempts = CouseQuizAttempts::where('course_quiz_id', $courseQuiz->id)
            ->where('user_id', $user->id)
            ->get();

        $inProgress = $userAttempts->whereNull('completed_at')->first();
        $completedCount = $userAttempts->whereNotNull('completed_at')->count();
        $maxAttempts = $courseQuiz->max_attempts ?? 1;

        if ($inProgress) {
            $this->attempt = $inProgress;
            $this->userAnswers = $inProgress->answers ?? [];
            $this->tabSwitchCount = $inProgress->tab_switch_count ?? 0;
        } else {
            // Check close date (due date) before starting a new attempt
            if ($courseQuiz->close_date && $now->gt(Carbon::parse($courseQuiz->close_date))) {
                Notification::make()
                    ->title("Quiz closed")
                    ->body("The due date for this quiz has passed.")
                    ->danger()
                    ->send();
                return redirect()->route('student-course-details', $subject->id);
            }

            // Check if max attempts reached
            if ($completedCount >= $maxAttempts) {
                Notification::make()
                    ->title("Maximum attempts reached")
                    ->body("You have already used all allowed attempts ({$maxAttempts}) for this quiz.")
                    ->danger()
                    ->send();
                return redirect()->route('student-course-details', $subject->id);
            }

            // Create new attempt
            $this->attempt = CouseQuizAttempts::create([
                'course_quiz_id' => $courseQuiz->id,
                'user_id' => $user->id,
                'score' => 0,
                'total_questions' => $this->totalQuestions,
                'started_at' => $now,
                'completed_at' => null,
                'answers' => [],
                'tab_switch_count' => 0,
                'violation_logs' => [],
            ]);
            $this->tabSwitchCount = 0;
        }

        // Determine deadline timestamp based on time_limit
        $startedAt = $this->attempt->started_at ? Carbon::parse($this->attempt->started_at) : $now;
        $deadline = $courseQuiz->time_limit ? (clone $startedAt)->addMinutes((int) $courseQuiz->time_limit) : null;

        if ($deadline) {
            $this->deadlineIso = $deadline->toIso8601String();

            // Auto-submit if time limit already passed
            if ($now->gte($deadline)) {
                $this->submitQuiz(true);
                return;
            }
        }
    }

    public function selectOption($questionId, $optionId)
    {
        if ($this->isSubmitted) {
            return;
        }

        $this->userAnswers[$questionId] = $optionId;

        if ($this->attempt && !$this->attempt->completed_at) {
            $this->attempt->update([
                'answers' => $this->userAnswers,
            ]);
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->totalQuestions - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < $this->totalQuestions) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function submitQuiz(bool $autoSubmitted = false)
    {
        if ($this->isSubmitted) {
            return;
        }

        $correctCount = 0;
        foreach ($this->courseQuiz->questions as $question) {
            $selectedOptionId = $this->userAnswers[$question->id] ?? null;
            $correctOption = $question->options->firstWhere('is_correct', true);

            if ($selectedOptionId && $correctOption && $selectedOptionId == $correctOption->id) {
                $correctCount++;
            }
        }

        $this->score = $correctCount;
        $this->isSubmitted = true;

        if ($this->attempt && !$this->attempt->completed_at) {
            $this->attempt->update([
                'score' => $this->score,
                'total_questions' => $this->totalQuestions,
                'completed_at' => Carbon::now(),
                'answers' => $this->userAnswers,
            ]);
        }

        Notification::make()
            ->title($autoSubmitted ? "Time expired - Quiz auto-submitted!" : "Quiz submitted successfully!")
            ->body("You scored {$this->score} out of {$this->totalQuestions}.")
            ->success()
            ->send();
    }

    public int $tabSwitchCount = 0;

    public function recordTabSwitch()
    {
        if ($this->isSubmitted || !$this->attempt) {
            return;
        }

        $this->tabSwitchCount++;
        $logs = is_array($this->attempt->violation_logs) ? $this->attempt->violation_logs : [];
        $logs[] = [
            'event' => 'tab_switch_or_blurred',
            'timestamp' => now()->toIso8601String(),
        ];

        $this->attempt->tab_switch_count = $this->tabSwitchCount;
        $this->attempt->violation_logs = $logs;
        $this->attempt->save();
    }

    public function render()
    {
        $currentQuestion = $this->courseQuiz->questions->values()->get($this->currentQuestionIndex);

        return view('livewire.take-course-quiz', [
            'currentQuestion' => $currentQuestion,
        ]);
    }
}
