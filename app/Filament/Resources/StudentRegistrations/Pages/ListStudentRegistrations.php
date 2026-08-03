<?php

namespace App\Filament\Resources\StudentRegistrations\Pages;

use App\Filament\Resources\StudentRegistrations\StudentRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentRegistrations extends ListRecords
{
    protected static string $resource = StudentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
