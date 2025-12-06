<?php

namespace App\Filament\Schemas;

class ClientSchema
{
    public static function schema(): array
    {
        return [
            \Filament\Infolists\Components\Section::make('Informations client')
                ->schema([
                    \Filament\Infolists\Components\TextEntry::make('name')
                        ->label('Nom'),
                    \Filament\Infolists\Components\TextEntry::make('email')
                        ->label('Email')
                        ->icon('heroicon-m-envelope'),
                    \Filament\Infolists\Components\TextEntry::make('phone')
                        ->label('Téléphone')
                        ->icon('heroicon-m-phone'),
                    \Filament\Infolists\Components\TextEntry::make('address')
                        ->label('Adresse')
                        ->columnSpanFull(),
                    \Filament\Infolists\Components\TextEntry::make('status')
                        ->label('Statut')
                        ->badge(),
                    \Filament\Infolists\Components\TextEntry::make('notes')
                        ->label('Notes')
                        ->columnSpanFull(),
                    \Filament\Infolists\Components\TextEntry::make('user.name')
                        ->label('Assigné à'),
                    \Filament\Infolists\Components\TextEntry::make('created_at')
                        ->label('Créé le')
                        ->dateTime(),
                    \Filament\Infolists\Components\TextEntry::make('updated_at')
                        ->label('Modifié le')
                        ->dateTime(),
                ])
                ->columns(2),
            \Filament\Infolists\Components\Section::make('Contacts associés')
                ->schema([
                    \Filament\Infolists\Components\RepeatableEntry::make('contacts')
                        ->label('')
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('name'),
                            \Filament\Infolists\Components\TextEntry::make('email'),
                            \Filament\Infolists\Components\TextEntry::make('phone'),
                        ])
                        ->columns(3),
                ]),
        ];
    }
}