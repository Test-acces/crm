<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ClientEvolutionChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Évolution des Clients (30 jours)';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        // Get client creation data for the last 30 days
        $data = $query->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill missing dates with 0
        $dates = [];
        $counts = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M j');
            $counts[] = $data[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Nouveaux Clients',
                    'data' => $counts,
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}