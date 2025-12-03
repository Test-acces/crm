<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CrmStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Clients', Client::count())
                ->description('Nombre total de clients')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
            Stat::make('Clients Actifs', Client::where('status', 'active')->count())
                ->description('Clients actifs')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),
            Stat::make('Clients Inactifs', Client::where('status', 'inactive')->count())
                ->description('Clients inactifs')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('danger'),
            Stat::make('Total Contacts', Contact::count())
                ->description('Nombre total de contacts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Tâches en cours', Task::where('status', 'in_progress')->count())
                ->description('Tâches actives')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
            Stat::make('Tâches terminées', Task::where('status', 'completed')->count())
                ->description('Tâches complétées')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Activités récentes', Activity::where('date', '>=', now()->subWeek())->count())
                ->description('Cette semaine')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),
        ];
    }
}