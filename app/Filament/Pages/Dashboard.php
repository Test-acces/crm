<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClientEvolutionWidget;
use App\Filament\Widgets\CrmStatsOverviewWidget;
use App\Filament\Widgets\RecentActivitiesWidget;
use App\Filament\Widgets\TaskOverviewWidget;
use App\Filament\Widgets\PerformanceIndicatorsWidget;
use App\Filament\Widgets\QuickActionsWidget;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardHeaderWidget::class,
            \App\Filament\Widgets\QuickActionsGridWidget::class,
            \App\Filament\Widgets\SidebarActivitiesWidget::class,
            \App\Filament\Widgets\GeneralOverviewWidget::class,
            \App\Filament\Widgets\PerformanceIndicatorsGridWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 3,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 3,
        ];
    }

    public function getHeaderActions(): array
    {
        return [
            // Actions can be added here if needed
        ];
    }
}