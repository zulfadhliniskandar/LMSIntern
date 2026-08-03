<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubjectsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('General details and code for this academic subject.')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Subject Name')
                                    ->placeholder('e.g. Introduction to Computer Science')
                                    ->prefixIcon('heroicon-o-book-open')
                                    ->helperText('Official title of the subject.')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(100),

                                TextInput::make('code')
                                    ->label('Subject Code')
                                    ->placeholder('e.g. CS101')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->helperText('Unique 3 to 5-character subject code.')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(5)
                                    ->dehydrateStateUsing(fn (?string $state): ?string => $state ? strtoupper($state) : null)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Describe the subject objectives, topics covered, and prerequisites...')
                            ->rows(3)
                            ->helperText('Brief overview of the subject syllabus.')
                            ->required()
                            ->minLength(5)
                            ->maxLength(500),
                    ]),

                Section::make('Academic & Financial Details')
                    ->description('Assign programme, instructor, credits, and tuition fee.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('programme_id')
                                    ->label('Academic Programme')
                                    ->relationship('programme', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-academic-cap')
                                    ->helperText('Select associated academic programme.')
                                    ->required(),

                                Select::make('instructor_id')
                                    ->label('Assigned Instructor')
                                    ->relationship('instructor', 'instructor_id')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user?->name ?? "Instructor #{$record->instructor_id}")
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-o-user')
                                    ->helperText('Select instructor teaching this subject.'),
                                    

                                TextInput::make('credit_hours')
                                    ->label('Credit Hours')
                                    ->placeholder('e.g. 3')
                                    ->prefixIcon('heroicon-o-calculator')
                                    ->helperText('Number of academic credit hours.')
                                    ->required()
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(10),

                                TextInput::make('fee')
                                    ->label('Subject Fee ($)')
                                    ->placeholder('e.g. 300.00')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->helperText('Tuition cost for this subject.')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0),
                            ]),
                    ]),
            ]);
    }
}

