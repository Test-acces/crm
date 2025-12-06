<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Activity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PriorityTasksWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected static ?string $height = '350px';

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        $topClients = $query->select('clients.id', 'clients.name', 'clients.status')
            ->selectRaw('COUNT(activities.id) as activity_count')
            ->leftJoin('activities', function ($join) {
                $join->on('clients.id', '=', 'activities.client_id')
                     ->where('activities.date', '>=', now()->subDays(30));
            })
            ->groupBy('clients.id', 'clients.name', 'clients.status')
            ->having('activity_count', '>', 0)
            ->orderBy('activity_count', 'desc')
            ->limit(5)
            ->get();

        $clientList = '';
        foreach ($topClients as $client) {
            $statusColor = match($client->status) {
                'active' => '🟢',
                'inactive' => '🔴',
                default => '⚪',
            };
            $clientList .= "<div style='margin-bottom: 6px;'><strong>{$client->name}</strong> {$statusColor} ({$client->activity_count} activités)</div>";
        }

        if (empty($clientList)) {
            $clientList = 'Aucun client actif';
        }

        return [
            Stat::make('Top Clients par Activité', $topClients->count())
                ->description($clientList)
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
        ];
    }
}