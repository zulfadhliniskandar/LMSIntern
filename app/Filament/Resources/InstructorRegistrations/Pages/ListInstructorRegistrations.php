<?php

namespace App\Filament\Resources\InstructorRegistrations\Pages;

use App\Filament\Resources\InstructorRegistrations\InstructorRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstructorRegistrations extends ListRecords
{
    protected static string $resource = InstructorRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
