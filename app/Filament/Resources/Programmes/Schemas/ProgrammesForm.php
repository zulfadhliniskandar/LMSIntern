<?php

namespace App\Filament\Resources\Programmes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgrammesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Programme Information')
                    ->description('General details and code for this academic subject.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Programme Name')
                                    ->placeholder('e.g. Bachelor of Science in Computer Science')
                                    ->prefixIcon('heroicon-o-academic-cap')
                                    ->helperText('Official title of the programme.')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(100),

                                TextInput::make('code')
                                    ->label('Programme Code')
                                    ->placeholder('e.g. BSC')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->helperText('Unique 2 to 5-character programme code.')
                                    ->required()
                                    ->minLength(2)
                                    ->maxLength(5)
                                    ->dehydrateStateUsing(fn (?string $state): ?string => $state ? strtoupper($state) : null)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Describe the programme objectives, topics covered, and prerequisites...')
                            ->rows(3)
                            ->helperText('Brief overview of the programme syllabus.')
                            ->required()
                            ->minLength(5)
                            ->maxLength(500),
                    ]),

                Section::make('Academic & Financial Details')
                    ->description('Assign department, total fee')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Select::make('department_id')
                                    ->label('Department')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-academic-cap')
                                    ->helperText('Select associated academic department.')
                                    ->required(),

                                TextInput::make('totalFee')
                                    ->label('Total Programme Fee ($)')
                                    ->placeholder('e.g. 12000.00')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->helperText('Total tuition fee cost for this programme.')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0),
                            ]),
                    ]),
            ]);
    }
}
