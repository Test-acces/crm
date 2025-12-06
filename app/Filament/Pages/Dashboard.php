<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClientEvolutionChartWidget;
use App\Filament\Widgets\ClientStatusChartWidget;
use App\Filament\Widgets\CrmStatsWidget;
use App\Filament\Widgets\PriorityTasksWidget;
use App\Filament\Widgets\RecentActivitiesStatsWidget;
use App\Filament\Widgets\TaskStatusChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Tableau de Bord CRM';

    public function getWidgets(): array
    {
        return [
            CrmStatsWidget::class,
            ClientEvolutionChartWidget::class,
            ClientStatusChartWidget::class,
            RecentActivitiesStatsWidget::class,
            TaskStatusChartWidget::class,
            PriorityTasksWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}