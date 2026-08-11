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
use Livewire\Component;

class ListStudentNotSubmitAssignment extends Component implements HasActions, HasForms, HasTable
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
                        $query->whereDoesntHave('submissions', function ($subQuery) {
                            $subQuery->where('assignment_id', $this->assignmentId);
                        });
                    })
                    ->with(['user', 'programme'])
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
            ])
            ->defaultSort('student_id', 'asc');
    }

    public function render()
    {
        return view('livewire.list-student-not-submit-assignment');
    }
}
