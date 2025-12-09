<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Task;
use App\Models\Activity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerformanceIndicatorsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        $clientsQuery = Client::query();
        $tasksQuery = Task::query();
        $activitiesQuery = Activity::query();

        if (auth()->user() && !auth()->user()->canSeeAllClients()) {
            $clientsQuery->where('user_id', auth()->id());
            $tasksQuery->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhereHas('client', function ($clientQuery) {
                      $clientQuery->where('user_id', auth()->id());
                  });
            });
            $activitiesQuery->whereHas('client', function ($query) {
                $query->where('user_id', auth()->id());
            });
        }

        // Task completion rate
        $totalTasks = (clone $tasksQuery)->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'completed')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        // Average tasks per client
        $totalClients = (clone $clientsQuery)->count();
        $avgTasksPerClient = $totalClients > 0 ? round($totalTasks / $totalClients, 1) : 0;

        // Activity frequency
        $activitiesThisMonth = (clone $activitiesQuery)->whereMonth('date', now()->month)->whereYear('date', now()->year)->count();
        $avgActivitiesPerDay = round($activitiesThisMonth / now()->day, 1);

        // Client growth trend
        $clientsLastMonth = (clone $clientsQuery)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
        $clientsThisMonth = (clone $clientsQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $growthRate = $clientsLastMonth > 0 ? round((($clientsThisMonth - $clientsLastMonth) / $clientsLastMonth) * 100, 1) : 0;

        return [
            Stat::make('Tâches/Client', $avgTasksPerClient)
                ->description('Moyenne par client')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),

            Stat::make('Activités/Jour', $avgActivitiesPerDay)
                ->description('Ce mois-ci')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Croissance Clients', $growthRate . '%')
                ->description('Tendance mensuelle')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($growthRate >= 0 ? 'success' : 'danger'),

            Stat::make('Nouveaux Clients', $clientsThisMonth)
                ->description('Ce mois-ci')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),
        ];
    }
}