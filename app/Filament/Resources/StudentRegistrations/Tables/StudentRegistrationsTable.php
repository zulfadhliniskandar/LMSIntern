<?php

namespace App\Filament\Resources\StudentRegistrations\Tables;

use App\Models\Programme;
use App\Models\Student;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction; 
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use App\Models\User;

class StudentRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gender')
                    ->searchable(),
                TextColumn::make('programme.name')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match($state){
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'gray',
                    }),
                
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    DeleteAction::make(),
                    Action::make('Approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible( fn($record) => $record->status === "Pending" )
                    ->action(function ($record) {
                        $record->update(['status' => 'Approved']);
                        $programmeCode = $record->programme->code ?? '';
                        do{
                            $randomDigits = mt_rand(00000000,99999999);
                            $studentId = $programmeCode.$randomDigits;
                        }
                        while(Student::where('student_id', $studentId)->exists());
                        
                        Student::firstOrCreate([
                                'user_id' => $record->user_id,
                                'student_id' => $studentId,
                                'programme_id' => $record->programme_id,
                            ]);
                        $user = User::findOrFail($record->user_id);
                             if ($user){
                                Notification::make()
                                    ->title('Student Approved')
                                    ->body('Congratulations! Your registration has been approved')
                                    ->success()
                                    ->sendToDatabase($user);
                             }
                        Notification::make()
                            ->title("Student Approved")
                            ->body("Student has been approved successfully!")
                            ->success()
                            ->send();
                    }),
                    Action::make('Reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible( fn($record) => $record->status === "Pending" )
                    ->action(function ($record) {
                        $record->update(['status' => 'Rejected']);
                        $user = User::findOrFail($record->user_id);
                             if ($user){
                                Notification::make()
                                    ->title('Student Rejected')
                                    ->body('Unfortunately, your registration has been rejected')
                                    ->danger()
                                    ->sendToDatabase($user);
                             }
                        Notification::make()
                            ->title("Student Rejected")
                            ->body("Student has been rejected successfully!")
                            ->danger()
                            ->send();
                    }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
