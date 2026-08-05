<?php

namespace App\Livewire;

use App\Models\advising as AdvisingModel;
use App\Models\subjectTaking;
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
use Livewire\Component;

class InstructorAdvisingCopy extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public ?AdvisingModel $selectedRequest = null;
    public bool $showDetailsModal = false;

    public function table(Table $table): Table
    {
        $instructorId = auth()->user()->instructor?->instructor_id;

        return $table
            ->query(
                AdvisingModel::query()
                    ->with(['student.user', 'student.programme'])
                    ->where('instructor_id', $instructorId)
                    ->latest()
            )
            ->recordClasses(function (AdvisingModel $record) {
                if ($record->status === 'Pending') {
                    return 'bg-rose-200/60 dark:bg-rose-950/20';
                }
                
            })
            ->columns([
                TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->copyable(),

                TextColumn::make('student.user.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user'),

                TextColumn::make('student.programme.name')
                    ->label('Programme')
                    ->badge()
                    ->color('gray')
                    ->placeholder('N/A')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Pending' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Approved' => 'heroicon-s-check-circle',
                        'Rejected' => 'heroicon-s-x-circle',
                        'Pending' => 'heroicon-s-clock',
                        default => 'heroicon-m-question-mark-circle',
                    }),

                TextColumn::make('created_at')
                    ->label('Requested Date')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->label('Filter by Status'),

                SelectFilter::make('programme')
                    ->relationship('student.programme', 'name')
                    ->label('Filter by Programme'),
            ])
            ->recordAction('viewDetails')
            ->actions([
                Action::make('viewDetails')
                    ->label('View Details')
                    ->icon('heroicon-m-eye')
                    ->color('primary')
                    ->modalHeading('Advising Request Details')
                    ->modalContent(fn (AdvisingModel $record) => view('livewire.partials.advising-details-modal', [
                        'selectedRequest' => $record,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                ActionGroup::make([
                    Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Advising Request')
                    ->modalDescription('Are you sure you want to approve this student\'s advising request?')
                    ->modalSubmitActionLabel('Yes, Approve')
                    ->visible(fn (AdvisingModel $record) => $record->status === 'Pending')
                    ->action(function (AdvisingModel $record) {
                        $this->approve($record->id);
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-s-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Advising Request')
                    ->modalDescription('Are you sure you want to reject this student\'s advising request?')
                    ->modalSubmitActionLabel('Yes, Reject')
                    ->visible(fn (AdvisingModel $record) => $record->status === 'Pending')
                    ->action(function (AdvisingModel $record) {
                        $this->reject($record->id);
                    }),

                DeleteAction::make()
                    ->hiddenLabel()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Advising Request')
                    ->modalDescription('Are you sure you want to delete this advising request?')
                    ->modalSubmitActionLabel('Yes, Delete')
                ])
            ])
            ->emptyStateHeading('No Advising Requests')
            ->emptyStateDescription('You currently have no advising requests.')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    public function approve($id)
    {
        $instructor = auth()->user()->instructor;
        if (! $instructor) {
            session()->flash('error', 'Instructor record not found.');
            return;
        }

        $request = AdvisingModel::findOrFail($id);

        if ($request->instructor_id !== $instructor->instructor_id) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $request->update([
            'status' => 'Approved',
        ]);

        $subjectIds = is_string($request->subject_ids) ? json_decode($request->subject_ids, true) : $request->subject_ids;
        if (is_array($subjectIds)) {
            foreach ($subjectIds as $subjectId) {
                subjectTaking::firstOrCreate([
                    'student_id' => $request->student_id,
                    'subject_id' => $subjectId,
                ]);
            }
        }

        $userToNotify = $request->student->user;
        if ($userToNotify) {
            Notification::make()
                ->title('Advising Request Approved')
                ->body('Your advising request has been approved successfully.')
                ->success()
                ->sendToDatabase($userToNotify);
        }

        Notification::make()
            ->title('Approved')
            ->body('Advising request approved successfully.')
            ->success()
            ->send();

        session()->flash('success', 'Advising request approved successfully.');
    }

    public function reject($id)
    {
        $instructor = auth()->user()->instructor;
        if (! $instructor) {
            session()->flash('error', 'Instructor record not found.');
            return;
        }

        $request = AdvisingModel::findOrFail($id);

        if ($request->instructor_id !== $instructor->instructor_id) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $request->update([
            'status' => 'Rejected',
        ]);

        $userToNotify = $request->student->user;
        if ($userToNotify) {
            Notification::make()
                ->title('Advising Request Rejected')
                ->body('Your advising request has been rejected.')
                ->danger()
                ->sendToDatabase($userToNotify);
        }

        Notification::make()
            ->title('Rejected')
            ->body('Advising request rejected successfully.')
            ->danger()
            ->send();

        session()->flash('success', 'Advising request rejected successfully.');
        return $this->redirect(route('instructor-advising-copy'), navigate: true);
    }

    public function render()
    {
        $instructorId = auth()->user()->instructor?->instructor_id;

        $stats = [
            'pending' => AdvisingModel::where('instructor_id', $instructorId)->where('status', 'Pending')->count(),
            'approved' => AdvisingModel::where('instructor_id', $instructorId)->where('status', 'Approved')->count(),
            'rejected' => AdvisingModel::where('instructor_id', $instructorId)->where('status', 'Rejected')->count(),
        ];

        return view('livewire.instructor-advising-copy', [
            'stats' => $stats,
        ]);
    }
}
