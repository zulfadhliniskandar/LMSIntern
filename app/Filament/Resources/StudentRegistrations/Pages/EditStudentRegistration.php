<?php

namespace App\Filament\Resources\StudentRegistrations\Pages;

use App\Filament\Resources\StudentRegistrations\StudentRegistrationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentRegistration extends EditRecord
{
    protected static string $resource = StudentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
