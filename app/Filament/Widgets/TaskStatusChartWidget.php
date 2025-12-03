<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TaskStatusChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tâches par Statut';

    protected function getData(): array
    {
        $pending = Task::where('status', 'pending')->count();
        $inProgress = Task::where('status', 'in_progress')->count();
        $completed = Task::where('status', 'completed')->count();

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