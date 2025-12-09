<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class ClientStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Clients par Statut';

    protected int | string | array $columnSpan = 1;

    protected static ?string $height = '400px';

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'accessibility' => [
                'enabled' => true,
            ],
        ];
    }

    protected function getFilteredClientQuery(): Builder
    {
        $query = Client::query();

        $user = auth()->user();

        if ($user && !$user->canSeeAllClients()) {
            // Commercial users only see their assigned clients
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public function getData(): array
    {
        $clientQuery = $this->getFilteredClientQuery();

        $active = (clone $clientQuery)->where('status', 'active')->count();
        $inactive = (clone $clientQuery)->where('status', 'inactive')->count();

        return [
            'datasets' => [
                [
                    'data' => [$active, $inactive],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.8)', // green for active
                        'rgba(239, 68, 68, 0.8)', // red for inactive
                    ],
                ],
            ],
            'labels' => ['Actifs', 'Inactifs'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}