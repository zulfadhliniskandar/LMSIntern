<?php

namespace App\Filament\Resources\InstructorRegistrations;

use App\Filament\Resources\InstructorRegistrations\Pages\CreateInstructorRegistration;
use App\Filament\Resources\InstructorRegistrations\Pages\EditInstructorRegistration;
use App\Filament\Resources\InstructorRegistrations\Pages\ListInstructorRegistrations;
use App\Filament\Resources\InstructorRegistrations\Schemas\InstructorRegistrationForm;
use App\Filament\Resources\InstructorRegistrations\Tables\InstructorRegistrationsTable;
use App\Models\InstructorRegistration;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstructorRegistrationResource extends Resource
{
    protected static ?string $model = InstructorRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'IntstuctorRegistration';
    protected static ?string $navigationLabel = 'Instructor Registration';

    protected static UnitEnum|string|null $navigationGroup = 'Registrations';


    public static function getNavigationBadge(): ?string
    {
        if (InstructorRegistration::where('status', 'pending')->count() > 0) {
            return InstructorRegistration::where('status', 'pending')->count();
        }
        return null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }
    public static function form(Schema $schema): Schema
    {
        return InstructorRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstructorRegistrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstructorRegistrations::route('/'),
            //'create' => CreateInstructorRegistration::route('/create'),
            'edit' => EditInstructorRegistration::route('/{record}/edit'),
        ];
    }
}
