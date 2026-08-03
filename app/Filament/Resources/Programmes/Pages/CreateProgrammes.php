<?php

namespace App\Filament\Resources\Programmes\Pages;

use App\Filament\Resources\Programmes\ProgrammesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProgrammes extends CreateRecord
{
    
    protected static string $resource = ProgrammesResource::class;
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
