<?php

namespace App\Filament\Resources;

use App\Filament\Actions\QuickLogActivityAction;
use App\Filament\Actions\ToggleClientStatusAction;
use App\Filament\Forms\ClientForm;
use App\Filament\Infolists\ClientInfolist;
use App\Filament\Pages as Pages;
use App\Filament\Tables\ClientTable;
use App\Models\Client;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-building-office';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'CRM Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema(ClientForm::schema());
    }

    public static function table(Table $table): Table
    {
        return ClientTable::configure($table)
            ->actions([
                Actions\ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make(),
                    ToggleClientStatusAction::make(),
                    QuickLogActivityAction::make(),
                    Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->visible(fn (Client $record) => $record->canBeDeleted()),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }


    public static function getRelations(): array
    {
        return [
            \App\Filament\RelationManagers\ContactsRelationManager::class,
            \App\Filament\RelationManagers\TasksRelationManager::class,
            \App\Filament\RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'view' => Pages\ViewClient::route('/{record}'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['contacts', 'tasks']);
    }
}