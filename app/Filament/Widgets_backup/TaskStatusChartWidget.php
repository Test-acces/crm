<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TaskStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Tâches par Statut';

    protected int | string | array $columnSpan = 1;

    protected static ?string $height = '350px';

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Horizontal bars
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }

    public function getData(): array
    {
        $query = Task::query();

        $user = auth()->user();

        if ($user && !$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      if ($user->hasRole('commercial')) {
                          $clientQuery->where('user_id', $user->id);
                      }
                  });
            });
        }

        $pending = (clone $query)->where('status', 'pending')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $completed = (clone $query)->where('status', 'completed')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Tâches',
                    'data' => [$pending, $inProgress, $completed],
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
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