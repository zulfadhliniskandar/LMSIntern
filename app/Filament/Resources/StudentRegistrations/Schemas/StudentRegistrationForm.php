<?php

namespace App\Filament\Resources\StudentRegistrations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class StudentRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('address'),
                TextInput::make('ic_passport')
                    ->required(),
                TextInput::make('age')
                    ->numeric(),
                TextInput::make('gender'),
                TextInput::make('programme_id')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected'])
                    ->default('Pending')
                    ->required(),
                FileUpload::make('document_path')
                    ->label('Uploaded Document')
                    ->disk('public')
                    ->directory('registrations')
                    ->visibility('public')
                    ->openable()
                    ->downloadable(),
            ]);
    }
}
