<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;

class AdviseeForAdvisorPage extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function table(Table $table): Table
    {
        $instructorId = auth()->user()->instructor?->instructor_id;

        return $table
            ->query(
                User::query()
                    ->with(['student.programme', 'student.appointments'])
                    ->whereHas('student', function ($query) use ($instructorId) {
                        $query->where('advisor', $instructorId);
                    })
            )
            ->recordClasses(function (User $record) {
                $hasPending = $record->student?->appointments->contains('status', 'pending') ||
                    \App\Models\AppointmentWithAdvisor::where('student_id', $record->id)->where('status', 'pending')->exists();

                return $hasPending ? 'bg-rose-50/70 dark:bg-rose-950/30' : '';
            })
            ->columns([
                TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->copyable(),

                TextColumn::make('name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user'),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope')
                    ->copyable(),

                TextColumn::make('student.programme.name')
                    ->label('Programme')
                    ->badge()
                    ->color('gray')
                    ->placeholder('N/A')
                    ->searchable(),

                TextColumn::make('appointment_status')
                    ->label('Appointment')
                    ->badge()
                    ->state(function (User $record) {
                        $hasPending = $record->student?->appointments->contains('status', 'pending') ||
                            \App\Models\AppointmentWithAdvisor::where('student_id', $record->id)->where('status', 'pending')->exists();

                        return $hasPending ? 'Pending Request' : 'Clear';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Pending Request' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Pending Request' => 'heroicon-s-exclamation-circle',
                        default => 'heroicon-m-check-circle',
                    }),
            ])
            ->filters([
                SelectFilter::make('programme')
                    ->relationship('student.programme', 'name')
                    ->label('Filter by Programme'),
            ])
            ->actions([
                Action::make('view')
                    ->label('View Profile')
                    ->icon('heroicon-m-eye')
                    ->color(function (User $record) {
                        $hasPending = $record->student?->appointments->contains('status', 'pending') ||
                            \App\Models\AppointmentWithAdvisor::where('student_id', $record->id)->where('status', 'pending')->exists();

                        return $hasPending ? 'danger' : 'primary';
                    })
                    ->url(fn (User $record) => \Illuminate\Support\Facades\Route::has('student-profile') ? route('student-profile', $record) : '#'),
            ])
            ->emptyStateHeading('No Advisees Assigned')
            ->emptyStateDescription('There are currently no students assigned to you as an advisor.')
            ->emptyStateIcon('heroicon-o-user-group');
    }

    public function render()
    {
        $instructorId = auth()->user()->instructor?->instructor_id;

        $adviseeCount = User::whereHas('student', function ($query) use ($instructorId) {
            $query->where('advisor', $instructorId);
        })->count();

        return view('livewire.advisee-for-advisor-page', [
            'adviseeCount' => $adviseeCount,
        ]);
    }
}

