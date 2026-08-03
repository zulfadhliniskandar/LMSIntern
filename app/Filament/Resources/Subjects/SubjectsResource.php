<?php

namespace App\Filament\Resources\Subjects;

use App\Filament\Resources\Subjects\Pages\CreateSubjects;
use App\Filament\Resources\Subjects\Pages\EditSubjects;
use App\Filament\Resources\Subjects\Pages\ListSubjects;
use App\Filament\Resources\Subjects\Schemas\SubjectsForm;
use App\Filament\Resources\Subjects\Tables\SubjectsTable;
use App\Models\Subjects;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Subjects\Pages\ViewSubjects;

class SubjectsResource extends Resource
{
    protected static ?string $model = Subjects::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'subject';
    protected static ?string $navigationLabel = 'Subjects';
    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';
    

    public static function form(Schema $schema): Schema
    {
        return SubjectsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubjects::route('/'),
            'create' => CreateSubjects::route('/create'),
            'edit' => EditSubjects::route('/{record}/edit'),
            'view' => ViewSubjects::route('/{record}'),
        ];
    }
}
