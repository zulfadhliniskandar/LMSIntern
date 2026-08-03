<?php

namespace App\Filament\Resources\InstructorRegistrations\Pages;

use App\Filament\Resources\InstructorRegistrations\InstructorRegistrationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstructorRegistration extends EditRecord
{
    protected static string $resource = InstructorRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
