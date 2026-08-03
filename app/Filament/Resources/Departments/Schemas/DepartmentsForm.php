<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Department Information')
                    ->description('Specify the official name and unique code for this academic department.')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Department Name')
                                    ->placeholder('e.g. Faculty of Computer Science & IT')
                                    ->prefixIcon('heroicon-o-academic-cap')
                                    ->helperText('Full official title of the department.')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(100),

                                TextInput::make('code')
                                    ->label('Department Code')
                                    ->placeholder('e.g. FCS')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->helperText('Unique 3 to 5-letter code.')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(5)
                                    ->dehydrateStateUsing(fn (?string $state): ?string => $state ? strtoupper($state) : null)
                                    ->unique(ignoreRecord: true),
                            ]),
                    ]),
            ]);
    }
}

