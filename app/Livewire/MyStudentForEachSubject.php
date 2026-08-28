<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\Subjects;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action as TableAction;
use Livewire\Component;

class MyStudentForEachSubject extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public $subjectId;
    public $subject;

    public function mount($subject = null)
    {
        $instructor = auth()->user()->instructor;
        if (!$instructor) {
            abort(403);
        }

        $this->subjectId = is_object($subject) ? $subject->id : $subject;
        if ($this->subjectId) {
            $this->subject = Subjects::with('programme')->find($this->subjectId);
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
                    ->with(['user', 'programme'])
            )
            ->columns([
                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->copyable(),

                TextColumn::make('user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user'),

                TextColumn::make('user.email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope')
                    ->copyable(),

                TextColumn::make('programme.name')
                    ->label('Programme')
                    ->badge()
                    ->color('gray')
                    ->placeholder('N/A')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('programme')
                    ->relationship('programme', 'name')
                    ->label('Filter by Programme'),
            ])
            // ->actions([
            //     TableAction::make('viewProfile')
            //         ->label('View Profile')
            //         ->icon('heroicon-m-eye')
            //         ->color('primary')
            //         ->url(fn (Student $record) => route('student-profile', $record->student_id)),
            // ])
            ->emptyStateHeading('No Students Enrolled')
            ->emptyStateDescription('There are currently no students registered for this course/subject.')
            ->emptyStateIcon('heroicon-o-user-group');
    }

    public function render()
    {
        $studentCount = Student::whereHas('subjectTaking', function ($query) {
            $query->where('subject_id', $this->subjectId);
        })->count();

        return view('livewire.my-student-for-each-subject', [
            'studentCount' => $studentCount,
        ]);
    }
}
