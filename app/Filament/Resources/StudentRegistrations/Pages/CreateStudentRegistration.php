<?php

namespace App\Filament\Resources\StudentRegistrations\Pages;

use App\Filament\Resources\StudentRegistrations\StudentRegistrationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentRegistration extends CreateRecord
{
    protected static string $resource = StudentRegistrationResource::class;
}
