<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuickActionsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Nouveau Client', '')
                ->description('Ajouter un client')
                ->descriptionIcon('heroicon-o-plus')
                ->color('primary')
                ->url(route('filament.admin.resources.clients.create')),

            Stat::make('Nouvelle Tâche', '')
                ->description('Créer une tâche')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('success')
                ->url(route('filament.admin.resources.tasks.create')),

            Stat::make('Nouveau Contact', '')
                ->description('Ajouter un contact')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('warning')
                ->url(route('filament.admin.resources.contacts.create')),
        ];
    }
}