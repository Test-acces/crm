<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        $query = Task::query();

        if (auth()->user() && !auth()->user()->canSeeAllClients()) {
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhereHas('client', function ($clientQuery) {
                      $clientQuery->where('user_id', auth()->id());
                  });
            });
        }

        $pending = (clone $query)->where('status', 'pending')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $overdue = (clone $query)->where('due_date', '<', now())->where('status', '!=', 'completed')->count();

        return [
            Stat::make('En attente', $pending)
                ->description('Tâches à faire')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('En cours', $inProgress)
                ->description('Tâches actives')
                ->descriptionIcon('heroicon-m-play')
                ->color('info'),

            Stat::make('Terminées', $completed)
                ->description('Tâches finies')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('En retard', $overdue)
                ->description('Tâches dépassées')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdue > 0 ? 'danger' : 'gray'),
        ];
    }
}