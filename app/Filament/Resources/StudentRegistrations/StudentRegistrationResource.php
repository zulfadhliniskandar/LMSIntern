<?php

namespace App\Filament\Resources\StudentRegistrations;

use App\Filament\Resources\StudentRegistrations\Pages\CreateStudentRegistration;
use App\Filament\Resources\StudentRegistrations\Pages\EditStudentRegistration;
use App\Filament\Resources\StudentRegistrations\Pages\ListStudentRegistrations;
use App\Filament\Resources\StudentRegistrations\Pages\ViewStudentRegistration;
use App\Filament\Resources\StudentRegistrations\Schemas\StudentRegistrationForm;
use App\Filament\Resources\StudentRegistrations\Tables\StudentRegistrationsTable;
use App\Models\StudentRegistration;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentRegistrationResource extends Resource
{
    protected static ?string $model = StudentRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'New Student Registration';

    protected static ?string $navigationLabel = 'Student Registration';

    protected static UnitEnum|string|null $navigationGroup = 'Registrations';

    public static function getNavigationBadge(): ?string
    {
        if (StudentRegistration::where('status', 'pending')->count() > 0) {
            return StudentRegistration::where('status', 'pending')->count();
        }
        return null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return StudentRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentRegistrationsTable::configure($table);
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
            'index' => ListStudentRegistrations::route('/'),
            'view' => ViewStudentRegistration::route('/{record}'),
            //'create' => CreateStudentRegistration::route('/create'),
            'edit' => EditStudentRegistration::route('/{record}/edit'),
        ];
    }
}
