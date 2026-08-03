<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\Instructor;

class StudentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('student_id')
                    ->label('Student ID')
                    ->disabled()
                    ->dehydrated(),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('advisor')
                    ->label('Advisor')
                    ->options(fn () => Instructor::with('user')->get()->pluck('user.name', 'instructor_id'))
                    ->searchable()
                    ->preload(),

                Select::make('programme_id')
                    ->relationship('programme', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
