<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Task;
use App\Models\Activity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CrmStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $user = auth()->user();

        // Base queries with user filtering
        $clientsQuery = Client::query();
        $contactsQuery = Contact::query();
        $tasksQuery = Task::query();
        $activitiesQuery = Activity::query();

        if ($user && !$user->canSeeAllClients()) {
            $clientsQuery->where('user_id', $user->id);
            $contactsQuery->whereHas('client', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
            $tasksQuery->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('client', function ($clientQuery) use ($user) {
                          $clientQuery->where('user_id', $user->id);
                      });
            });
            $activitiesQuery->whereHas('client', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $totalClients = (clone $clientsQuery)->count();
        $activeClients = (clone $clientsQuery)->where('status', 'active')->count();
        $totalTasks = (clone $tasksQuery)->count();
        $pendingTasks = (clone $tasksQuery)->where('status', 'pending')->count();
        $totalActivities = (clone $activitiesQuery)->count();
        $recentActivities = (clone $activitiesQuery)->where('date', '>=', now()->subDays(7))->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'completed')->count();

        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        return [
            Stat::make('Total Clients', $totalClients)
                ->description("{$activeClients} actifs")
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary')
                ->chart($this->getClientGrowthChart()),

            Stat::make('Total Tâches', $totalTasks)
                ->description("{$pendingTasks} en attente")
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning')
                ->chart($this->getTaskStatusChart()),


            Stat::make('Activités (7j)', $recentActivities)
                ->description("Sur {$totalActivities} total")
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->chart($this->getActivityTrendChart()),

            Stat::make('Taux Completion', $completionRate . '%')
                ->description("{$completedTasks} terminées")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($this->getCompletionRateChart()),
        ];
    }

    protected function getClientGrowthChart(): array
    {
        // Simple growth indicator - you can enhance this with actual historical data
        $currentMonth = Client::whereMonth('created_at', now()->month)->count();
        $lastMonth = Client::whereMonth('created_at', now()->subMonth()->month)->count();

        if ($lastMonth > 0) {
            $growth = round((($currentMonth - $lastMonth) / $lastMonth) * 100);
            return [$lastMonth, $currentMonth];
        }

        return [0, $currentMonth];
    }

    protected function getTaskStatusChart(): array
    {
        $user = auth()->user();
        $tasksQuery = Task::query();

        if ($user && !$user->canSeeAllClients()) {
            $tasksQuery->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('client', function ($clientQuery) use ($user) {
                          $clientQuery->where('user_id', $user->id);
                      });
            });
        }

        $pending = (clone $tasksQuery)->where('status', 'pending')->count();
        $inProgress = (clone $tasksQuery)->where('status', 'in_progress')->count();
        $completed = (clone $tasksQuery)->where('status', 'completed')->count();

        return [$pending, $inProgress, $completed];
    }

    protected function getActivityTrendChart(): array
    {
        $user = auth()->user();
        $activitiesQuery = Activity::query();

        if ($user && !$user->canSeeAllClients()) {
            $activitiesQuery->whereHas('client', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Activities for last 7 days
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = (clone $activitiesQuery)->whereDate('date', $date)->count();
            $data[] = $count;
        }

        return $data;
    }

    protected function getCompletionRateChart(): array
    {
        $user = auth()->user();
        $tasksQuery = Task::query();

        if ($user && !$user->canSeeAllClients()) {
            $tasksQuery->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('client', function ($clientQuery) use ($user) {
                          $clientQuery->where('user_id', $user->id);
                      });
            });
        }

        $totalTasks = (clone $tasksQuery)->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'completed')->count();

        if ($totalTasks > 0) {
            $completionRate = round(($completedTasks / $totalTasks) * 100);
            return [$totalTasks - $completedTasks, $completedTasks];
        }

        return [0, 0];
    }
}