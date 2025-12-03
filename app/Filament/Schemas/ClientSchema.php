<?php

namespace App\Filament\Schemas;

use Filament\Schemas;

class ClientSchema
{
    public static function schema(): array
    {
        return [
            Schemas\Components\Section::make('Client Information')
                ->schema([
                    Schemas\Components\TextEntry::make('name')
                        ->label('Name'),
                    Schemas\Components\TextEntry::make('email')
                        ->label('Email'),
                    Schemas\Components\TextEntry::make('phone')
                        ->label('Phone'),
                    Schemas\Components\TextEntry::make('address')
                        ->label('Address')
                        ->columnSpanFull(),
                    Schemas\Components\TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'active' => 'success',
                            'inactive' => 'danger',
                        }),
                    Schemas\Components\TextEntry::make('notes')
                        ->label('Notes')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Schemas\Components\Section::make('Timestamps')
                ->schema([
                    Schemas\Components\TextEntry::make('created_at')
                        ->label('Created At')
                        ->dateTime(),
                    Schemas\Components\TextEntry::make('updated_at')
                        ->label('Updated At')
                        ->dateTime(),
                ])
                ->columns(2)
                ->collapsed(),
        ];
    }
}