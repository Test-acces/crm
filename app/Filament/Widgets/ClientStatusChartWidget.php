<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;

class ClientStatusChartWidget extends ChartWidget
{
    protected ?string $heading = 'Clients par Statut';

    protected function getData(): array
    {
        $active = Client::where('status', 'active')->count();
        $inactive = Client::where('status', 'inactive')->count();

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