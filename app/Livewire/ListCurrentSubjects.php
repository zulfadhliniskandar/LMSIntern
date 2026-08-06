<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\subjectTaking;


class ListCurrentSubjects extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public $studentId = null;

    public function table(Table $table): Table
    {
        $query = subjectTaking::query()
            ->with(['subject', 'student.user', 'student.programme']);

        if ($this->studentId) {
            $query->where(function ($q) {
                $q->where('student_id', $this->studentId)
                  ->orWhereHas('student', fn ($sq) => $sq->where('user_id', $this->studentId));
            });
        } elseif (auth()->user()->instructor) {
            $adviseeIds = auth()->user()->instructor->advisees()->pluck('student_id')->toArray();
            $query->whereIn('student_id', $adviseeIds);
        } elseif (auth()->user()->student) {
            $query->where('student_id', auth()->user()->student->student_id);
        } else {
            $query->whereRaw('1 = 0');
        }

        return $table
            ->query($query->latest())
            ->columns([
                TextColumn::make('subject.code')
                    ->label('Subject Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->copyable(),

                TextColumn::make('subject.name')
                    ->label('Subject Name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->copyable(),

                TextColumn::make('subject.credit_hours')
                    ->label('Credit Hours')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->placeholder('N/A'),

                TextColumn::make('created_at')
                    ->label('Enrolled Date')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('subject')
                    ->relationship('subject', 'name')
                    ->label('Filter by Subject'),
            ])
            ->emptyStateHeading('No Currently Enrolled Subjects')
            ->emptyStateDescription('This advisee is not currently taking any active subjects.')
            ->emptyStateIcon('heroicon-o-book-open');
    }
    
    public function render()
    {
        return view('livewire.list-current-subjects');
    }
}
