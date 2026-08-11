<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\Student;
use App\Models\Subjects;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Livewire\Component;

class ListStudentSubmitAssignment extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;
    public $subjectId;
    public $assignmentId;
    public $assignment;
    public $subject;

    public function mount($subject = null, $assignment = null)
    {
        $this->subjectId = is_object($subject) ? $subject->id : $subject;
        $this->assignmentId = is_object($assignment) ? $assignment->id : $assignment;

        if ($this->subjectId) {
            $this->subject = Subjects::find($this->subjectId);
        }

        if ($this->assignmentId) {
            $this->assignment = Assignment::with('subject')->find($this->assignmentId);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()
                    ->when($this->subjectId, function ($query) {
                        $query->whereHas('subjectTaking', function ($stQuery) {
                            $stQuery->where('subject_id', $this->subjectId);
                        });
                    })
                    ->when($this->assignmentId, function ($query) {
                        $query->whereHas('submissions', function ($subQuery) {
                            $subQuery->where('assignment_id', $this->assignmentId);
                        });
                    })
                    ->with(['user', 'programme', 'submissions' => function ($query) {
                        if ($this->assignmentId) {
                            $query->where('assignment_id', $this->assignmentId);
                        }
                    }])
            )
            ->columns([
                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email Address')
                    ->searchable(),
                TextColumn::make('programme.name')
                    ->label('Programme')
                    ->placeholder('N/A'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function (Student $record) {
                        $submission = $record->submissions
                            ->where('assignment_id', $this->assignmentId)
                            ->first()
                            ?? \App\Models\AssignmentSubmission::where('student_id', $record->student_id)
                                ->where('assignment_id', $this->assignmentId)
                                ->first();

                        if (!$submission) {
                            return 'pending';
                        }

                        $dueDate = $this->assignment?->due_date;
                        if (!$dueDate) {
                            return 'on_time';
                        }

                        return \Carbon\Carbon::parse($submission->created_at)->lte(\Carbon\Carbon::parse($dueDate))
                            ? 'on_time'
                            : 'late';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'on_time' => 'success',
                        'late' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'on_time' => 'On Time',
                        'late' => 'Late',
                        default => ucfirst($state),
                    }),

            ])
            ->defaultSort('student_id', 'asc')
            ->actions([
                Action::make('viewSubmission')
                    ->label('View Details')
                    ->icon('heroicon-m-eye')
                    ->color('primary')
                    ->url(function (Student $record) {
                        $sub = \App\Models\AssignmentSubmission::where('student_id', $record->student_id)
                            ->where('assignment_id', $this->assignmentId)
                            ->first();

                        if (!$sub) {
                            return '#';
                        }

                        return route('submission-details', [
                            'subject' => $this->subjectId ?? ($this->assignment?->subjects_id ?? 1),
                            'assignment' => $this->assignmentId ?? 1,
                            'submission' => $sub->id,
                        ]);
                    }),
            ]);
    }
    public function render()
    {
        return view('livewire.list-student-submit-assignment');
    }
}
