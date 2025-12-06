<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Client;
use App\Models\Task;
use App\Models\Activity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        $user = auth()->user();

        // Only show for admins or managers
        if (!$user || !$user->isAdmin()) {
            return [];
        }

        $commercialUsers = User::where('role', 'commercial')->get();

        $stats = [];

        foreach ($commercialUsers as $commercial) {
            $clientsCount = Client::where('user_id', $commercial->id)->count();
            $activeClientsCount = Client::where('user_id', $commercial->id)
                ->where('status', 'active')
                ->count();
            $tasksCompleted = Task::where('user_id', $commercial->id)
                ->where('status', 'completed')
                ->where('updated_at', '>=', now()->subMonth())
                ->count();
            $activitiesCount = Activity::where('user_id', $commercial->id)
                ->where('date', '>=', now()->subMonth())
                ->count();

            $stats[] = Stat::make($commercial->name, $activeClientsCount)
                ->description("Clients actifs: {$activeClientsCount}/{$clientsCount} | Tâches: {$tasksCompleted} | Activités: {$activitiesCount}")
                ->descriptionIcon('heroicon-m-user-group')
                ->color($activeClientsCount > 0 ? 'success' : 'warning');
        }

        return $stats;
    }
}