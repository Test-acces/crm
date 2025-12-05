<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class CrmStatsWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->canSeeAllClients() ?? false;
    }

    protected function getFilteredClientQuery(): Builder
    {
        $query = Client::query();

        $user = auth()->user();

        if ($user && !$user->canSeeAllClients()) {
            // Commercial users only see their assigned clients
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    protected function getFilteredContactQuery(): Builder
    {
        $query = Contact::query();

        $user = auth()->user();

        if ($user && !$user->canSeeAllClients()) {
            // Commercial users only see contacts of their assigned clients
            $query->whereHas('client', function ($clientQuery) use ($user) {
                $clientQuery->where('user_id', $user->id);
            });
        }

        return $query;
    }

    protected function getFilteredTaskQuery(): Builder
    {
        $query = Task::query();

        $user = auth()->user();

        if ($user && !$user->isAdmin()) {
            // Non-admins can only see tasks assigned to them or related to their clients
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      if ($user->hasRole('commercial')) {
                          $clientQuery->where('user_id', $user->id);
                      }
                  });
            });
        }

        return $query;
    }

    protected function getFilteredActivityQuery(): Builder
    {
        $query = Activity::query();

        $user = auth()->user();

        if ($user && !$user->canSeeAllClients()) {
            // Filter activities by accessible clients
            $query->whereHas('client', function ($clientQuery) use ($user) {
                $clientQuery->where('user_id', $user->id);
            });
        }

        return $query;
    }

    protected function getStats(): array
    {
        $clientQuery = $this->getFilteredClientQuery();
        $contactQuery = $this->getFilteredContactQuery();
        $taskQuery = $this->getFilteredTaskQuery();
        $activityQuery = $this->getFilteredActivityQuery();

        return [
            Stat::make('Total Clients', $clientQuery->count())
                ->description('Nombre total de clients')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
            Stat::make('Clients Actifs', (clone $clientQuery)->where('status', 'active')->count())
                ->description('Clients actifs')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),
            Stat::make('Clients Inactifs', (clone $clientQuery)->where('status', 'inactive')->count())
                ->description('Clients inactifs')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('danger'),
            Stat::make('Total Contacts', $contactQuery->count())
                ->description('Nombre total de contacts')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Tâches en cours', (clone $taskQuery)->where('status', 'in_progress')->count())
                ->description('Tâches actives')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
            Stat::make('Tâches terminées', (clone $taskQuery)->where('status', 'completed')->count())
                ->description('Tâches complétées')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Activités récentes', (clone $activityQuery)->where('date', '>=', now()->subWeek())->count())
                ->description('Cette semaine')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),
        ];
    }
}