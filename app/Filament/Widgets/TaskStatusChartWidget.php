<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class TaskStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Tâches par Statut';

    protected int | string | array $columnSpan = 1;

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

    protected function getData(): array
    {
        $taskQuery = $this->getFilteredTaskQuery();

        $pending = (clone $taskQuery)->where('status', 'pending')->count();
        $inProgress = (clone $taskQuery)->where('status', 'in_progress')->count();
        $completed = (clone $taskQuery)->where('status', 'completed')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Tâches',
                    'data' => [$pending, $inProgress, $completed],
                    'backgroundColor' => [
                        'rgba(156, 163, 175, 0.8)', // gray for pending
                        'rgba(245, 158, 11, 0.8)', // amber for in_progress
                        'rgba(34, 197, 94, 0.8)', // green for completed
                    ],
                ],
            ],
            'labels' => ['En attente', 'En cours', 'Terminées'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}