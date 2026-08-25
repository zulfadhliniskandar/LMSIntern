<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\Student;
use App\Models\Subjects;
use App\Models\CourseQuizzes;
use App\Models\CouseQuizAttempts;
use Carbon\Carbon;

class DetailsEachQuiz extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $subjectId;
    public $quizId;
    public $quiz;
    public $subject;

    public ?CouseQuizAttempts $selectedAttemptForModal = null;
    public bool $showAttemptModal = false;

    public function mount($subject = null, $quiz = null)
    {
        if (!auth()->user()?->isInstructor()) {
            abort(403);
        }

        $this->subjectId = is_object($subject) ? $subject->id : $subject;
        $this->quizId = is_object($quiz) ? $quiz->id : $quiz;

        if ($this->subjectId) {
            $this->subject = Subjects::find($this->subjectId);
        }

        if ($this->quizId) {
            $this->quiz = CourseQuizzes::with(['subject', 'questions.options'])->find($this->quizId);
        }

        if (!$this->quiz || !$this->subject) {
            abort(404);
        }
    }

    public function viewAttemptDetails(int $attemptId)
    {
        $this->selectedAttemptForModal = CouseQuizAttempts::with(['user', 'quiz.questions.options'])->find($attemptId);
        $this->showAttemptModal = true;
    }

    public function closeAttemptModal()
    {
        $this->showAttemptModal = false;
        $this->selectedAttemptForModal = null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()
                    ->whereHas('subjectTaking', function ($query) {
                        $query->where('subject_id', $this->subjectId);
                    })
                    ->with(['user', 'programme'])
            )
            ->columns([
                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Student $record): string => $record->user->email ?? ''),

                TextColumn::make('programme.name')
                    ->label('Programme')
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('attempts_count')
                    ->label('Attempts')
                    ->getStateUsing(function (Student $record) {
                        $attemptsCount = CouseQuizAttempts::where('course_quiz_id', $this->quizId)
                            ->where('user_id', $record->user_id)
                            ->whereNotNull('completed_at')
                            ->count();
                        $max = $this->quiz->max_attempts ?? 1;
                        return "{$attemptsCount} / {$max}";
                    })
                    ->badge()
                    ->color('gray'),

                TextColumn::make('score')
                    ->label('Score')
                    ->getStateUsing(function (Student $record) {
                        $attempt = CouseQuizAttempts::where('course_quiz_id', $this->quizId)
                            ->where('user_id', $record->user_id)
                            ->orderByDesc('score')
                            ->first();

                        if (!$attempt || !$attempt->completed_at) {
                            return 'N/A';
                        }

                        $pct = round(($attempt->score / max(1, $attempt->total_questions)) * 100);
                        return "{$attempt->score}/{$attempt->total_questions} ({$pct}%)";
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        if ($state === 'N/A') return 'gray';
                        preg_match('/\((\d+)%\)/', $state, $matches);
                        $pct = isset($matches[1]) ? (int)$matches[1] : 0;
                        if ($pct >= 80) return 'success';
                        if ($pct >= 50) return 'warning';
                        return 'danger';
                    }),

                TextColumn::make('tab_switches')
                    ->label('Integrity')
                    ->getStateUsing(function (Student $record) {
                        $attempt = CouseQuizAttempts::where('course_quiz_id', $this->quizId)
                            ->where('user_id', $record->user_id)
                            ->latest()
                            ->first();

                        if (!$attempt) return 'Not Attempted';
                        $switches = $attempt->tab_switch_count ?? 0;
                        return $switches > 0 ? "{$switches} Tab Switches" : 'Clean Attempt';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Clean Attempt' => 'success',
                        'Not Attempted' => 'gray',
                        default => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Clean Attempt' => 'heroicon-m-check-circle',
                        'Not Attempted' => 'heroicon-m-minus-circle',
                        default => 'heroicon-m-exclamation-triangle',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function (Student $record) {
                        $attempt = CouseQuizAttempts::where('course_quiz_id', $this->quizId)
                            ->where('user_id', $record->user_id)
                            ->latest()
                            ->first();

                        if (!$attempt) return 'not_attempted';
                        if ($attempt->completed_at) return 'completed';
                        return 'in_progress';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => 'Completed',
                        'in_progress' => 'In Progress',
                        'not_attempted' => 'Not Attempted',
                        default => ucfirst($state),
                    }),

                TextColumn::make('completed_at')
                    ->label('Submitted At')
                    ->getStateUsing(function (Student $record) {
                        $attempt = CouseQuizAttempts::where('course_quiz_id', $this->quizId)
                            ->where('user_id', $record->user_id)
                            ->whereNotNull('completed_at')
                            ->latest('completed_at')
                            ->first();

                        return $attempt && $attempt->completed_at
                            ? Carbon::parse($attempt->completed_at)->format('M d, Y - h:i A')
                            : 'N/A';
                    })
                    ->sortable(),
            ])
            ->defaultSort('student_id', 'asc')
            ->actions([
                Action::make('viewAttempt')
                    ->label('View Attempt')
                    ->icon('heroicon-m-eye')
                    ->color('primary')
                    ->action(function (Student $record) {
                        $attempt = CouseQuizAttempts::where('course_quiz_id', $this->quizId)
                            ->where('user_id', $record->user_id)
                            ->latest()
                            ->first();

                        if ($attempt) {
                            $this->viewAttemptDetails($attempt->id);
                        }
                    })
                    ->visible(function (Student $record) {
                        return CouseQuizAttempts::where('course_quiz_id', $this->quizId)
                            ->where('user_id', $record->user_id)
                            ->exists();
                    }),
            ]);
    }

    public function render()
    {
        $enrolledStudentsCount = Student::whereHas('subjectTaking', function ($q) {
            $q->where('subject_id', $this->subjectId);
        })->count();

        $allAttempts = CouseQuizAttempts::where('course_quiz_id', $this->quizId)->get();
        $completedAttempts = $allAttempts->whereNotNull('completed_at');
        $uniqueStudentsAttempted = $allAttempts->pluck('user_id')->unique()->count();
        
        $avgScorePct = 0;
        if ($completedAttempts->count() > 0) {
            $totalPct = $completedAttempts->sum(function ($att) {
                return ($att->score / max(1, $att->total_questions)) * 100;
            });
            $avgScorePct = round($totalPct / $completedAttempts->count(), 1);
        }

        $flaggedAttemptsCount = $allAttempts->where('tab_switch_count', '>', 0)->count();

        return view('livewire.details-each-quiz', [
            'enrolledStudentsCount' => $enrolledStudentsCount,
            'uniqueStudentsAttempted' => $uniqueStudentsAttempted,
            'completedAttemptsCount' => $completedAttempts->count(),
            'avgScorePct' => $avgScorePct,
            'flaggedAttemptsCount' => $flaggedAttemptsCount,
        ]);
    }
}
