<?php

namespace App\Filament\Tables;

use App\Models\ClientStatus;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class ClientTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->tooltip('Nom du client'),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->tooltip('Adresse email'),
                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-m-phone')
                    ->tooltip('Numéro de téléphone'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state->label())
                    ->color(fn ($state): string => $state->color())
                    ->tooltip('Statut du client'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigné à')
                    ->sortable()
                    ->tooltip('Utilisateur assigné'),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50)
                    ->tooltip('Notes complètes'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->tooltip('Date de création'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ClientStatus::options()),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Aucun client trouvé')
            ->emptyStateDescription('Créez votre premier client pour commencer à gérer vos relations clients.')
            ->emptyStateIcon('heroicon-o-building-office')
            ->emptyStateActions([
                Actions\Action::make('create')
                    ->label('Créer un client')
                    ->url(fn () => route('filament.admin.resources.clients.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]);
    }
}