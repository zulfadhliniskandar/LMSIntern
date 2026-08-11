<?php

namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use App\Models\LearningMaterials;
use App\Models\QuizAttempts;
use App\Models\Quiz;

class ListOfQuizzes extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function getStudentId(): string
    {
        return auth()->user()?->student?->student_id ?? (string) auth()->id();
    }

    public function table(Table $table): Table
    {
        $studentId = $this->getStudentId();

        $query = Quiz::query()
            ->with(['learningMaterial'])
            ->where(function ($q) use ($studentId) {
                $q->whereHas('learningMaterial', fn ($lm) => $lm->where('student_id', $studentId))
                  ->orWhereHas('attempts', fn ($att) => $att->where('student_id', $studentId));
            })
            ->withCount(['attempts as attempts_count' => fn ($q) => $q->where('student_id', $studentId)]);

        return $table
            ->query($query->latest())
            ->columns([
                TextColumn::make('title')
                    ->label('Quiz Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-academic-cap')
                    ->iconColor('purple'),

                TextColumn::make('learningMaterial.title')
                    ->label('Study Material')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->placeholder('N/A')
                    ->limit(30),

                TextColumn::make('total_questions')
                    ->label('Questions')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->suffix(' Qs'),

                TextColumn::make('attempts_count')
                    ->label('Attempts')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'purple' : 'gray')
                    ->formatStateUsing(fn (int $state): string => $state . ($state === 1 ? ' attempt' : ' attempts')),

                TextColumn::make('latest_score')
                    ->label('Latest Score')
                    ->getStateUsing(function (Quiz $record) use ($studentId): string {
                        $latestAttempt = $record->attempts()
                            ->where('student_id', $studentId)
                            ->latest()
                            ->first();

                        if (! $latestAttempt) {
                            return 'Not Attempted';
                        }

                        $percentage = round(($latestAttempt->score / max(1, $latestAttempt->total_questions)) * 100);
                        return "{$latestAttempt->score} / {$latestAttempt->total_questions} ({$percentage}%)";
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        if ($state === 'Not Attempted') {
                            return 'gray';
                        }
                        if (str_contains($state, '%')) {
                            preg_match('/\((\d+)%\)/', $state, $matches);
                            $pct = isset($matches[1]) ? (int) $matches[1] : 0;
                            if ($pct >= 80) return 'success';
                            if ($pct >= 50) return 'warning';
                            return 'danger';
                        }
                        return 'info';
                    }),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('retake')
                    ->label(fn (Quiz $record): string => ($record->attempts_count ?? 0) > 0 ? 'Retake Quiz' : 'Start Quiz')
                    ->icon(fn (Quiz $record): string => ($record->attempts_count ?? 0) > 0 ? 'heroicon-o-arrow-path' : 'heroicon-o-play')
                    ->color(fn (Quiz $record): string => ($record->attempts_count ?? 0) > 0 ? 'primary' : 'success')
                    ->button()
                    ->url(fn (Quiz $record): string => route('quizzes.show', $record->id)),
            ])
            ->emptyStateHeading('No Quizzes Found')
            ->emptyStateDescription('Generate your first AI Quiz to test your understanding!')
            ->emptyStateIcon('heroicon-o-question-mark-circle');
    }

    public function render()
    {
        return view('livewire.list-of-quizzes');
    }
}
