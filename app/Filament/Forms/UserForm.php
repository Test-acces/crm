<?php

namespace App\Filament\Forms;

use Filament\Schemas;

class UserForm
{
    public static function schema(): array
    {
        return [
            Schemas\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),
            Schemas\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(255),
            Schemas\Components\Select::make('role')
                ->label('Role')
                ->options([
                    'admin' => 'Admin',
                    'manager' => 'Manager',
                    'commercial' => 'Commercial',
                    'viewer' => 'Viewer',
                ])
                ->default('viewer')
                ->required(),
            Schemas\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create')
                ->helperText('Leave blank to keep current password.'),
        ];
    }
}