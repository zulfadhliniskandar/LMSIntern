<?php

namespace App\Filament\Resources\Instructors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InstructorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->label('User ID')
                    ->disabled(),
                TextInput::make('department_name')
                    ->label('Department')
                    ->afterStateHydrated(fn ($component, $record) => $component->state($record?->department?->name)),
                TextInput::make('instructor_id')
                    ->label('Instructor ID')
                    ->required(),
                TextInput::make('user_name')
                    ->label('Instructor Name')
                    ->afterStateHydrated(fn ($component, $record) => $component->state($record?->user?->name)),
            ]);
    }
}
