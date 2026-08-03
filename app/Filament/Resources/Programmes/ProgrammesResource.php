<?php

namespace App\Filament\Resources\Programmes;

use App\Filament\Resources\Programmes\Pages\CreateProgrammes;
use App\Filament\Resources\Programmes\Pages\EditProgrammes;
use App\Filament\Resources\Programmes\Pages\ListProgrammes;
use App\Filament\Resources\Programmes\Schemas\ProgrammesForm;
use App\Filament\Resources\Programmes\Tables\ProgrammesTable;
use App\Models\Programme;
use BackedEnum;
use UnitEnum; 
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Programmes\Pages\ViewProgrammes;
class ProgrammesResource extends Resource
{
    protected static ?string $model = Programme::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'programmes';

    protected static ?string $navigationLabel = 'Programs';
    
    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';
    public static function form(Schema $schema): Schema
    {
        return ProgrammesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgrammesTable::configure($table);
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
            'index' => ListProgrammes::route('/'),
            'create' => CreateProgrammes::route('/create'),
            'edit' => EditProgrammes::route('/{record}/edit'),
            'view' => ViewProgrammes::route('/{record}'),
        ];
    }
}
