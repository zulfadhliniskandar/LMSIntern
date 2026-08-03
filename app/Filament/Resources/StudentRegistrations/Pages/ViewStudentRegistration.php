<?php

namespace App\Filament\Resources\StudentRegistrations\Pages;

use App\Filament\Resources\StudentRegistrations\StudentRegistrationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentRegistration extends ViewRecord
{
    protected static string $resource = StudentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
