<?php

namespace App\Filament\Resources\InstructorRegistrations\Pages;

use App\Filament\Resources\InstructorRegistrations\InstructorRegistrationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInstructorRegistration extends ViewRecord
{
    protected static string $resource = InstructorRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
