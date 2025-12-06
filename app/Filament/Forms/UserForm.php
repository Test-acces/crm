<?php

namespace App\Filament\Forms;

use Filament\Forms;

class UserForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255)
                ->extraAttributes(['aria-describedby' => 'name-help']),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(255)
                ->extraAttributes(['aria-describedby' => 'email-help']),
            Forms\Components\Select::make('role')
                ->label('Role')
                ->options(\App\Models\UserRole::options())
                ->default('viewer')
                ->required()
                ->extraAttributes(['aria-describedby' => 'role-help']),
            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create')
                ->helperText('Leave blank to keep current password.')
                ->extraAttributes(['aria-describedby' => 'password-help']),
        ];
    }
}