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
    protected static ?int $sort = 1;


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

    public function getStats(): array
    {
        $user = auth()->user();
        $cacheKey = 'crm_stats_' . ($user ? $user->id : 'guest');

        return \Cache::remember($cacheKey, 300, function () use ($user) { // Cache for 5 minutes
            $clientQuery = $this->getFilteredClientQuery();
            $contactQuery = $this->getFilteredContactQuery();
            $taskQuery = $this->getFilteredTaskQuery();
            $activityQuery = $this->getFilteredActivityQuery();

            $timestamp = now()->format('H:i:s');
            return [
                Stat::make('Clients', $clientQuery->count())
                    ->description('Actifs: ' . (clone $clientQuery)->where('status', 'active')->count() . ' | Mis à jour: ' . $timestamp)
                    ->descriptionIcon('heroicon-m-building-office')
                    ->color('primary'),
                Stat::make('Tâches', (clone $taskQuery)->where('status', 'in_progress')->count())
                    ->description('En cours | Mis à jour: ' . $timestamp)
                    ->descriptionIcon('heroicon-m-clipboard-document-list')
                    ->color('warning'),
                Stat::make('Activités', (clone $activityQuery)->where('date', '>=', now()->subWeek())->count())
                    ->description('Cette semaine | Mis à jour: ' . $timestamp)
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('gray'),
                Stat::make('Contacts', $contactQuery->count())
                    ->description('Total | Mis à jour: ' . $timestamp)
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('info'),
            ];
        });
    }
}