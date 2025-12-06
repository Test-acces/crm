<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ClientEvolutionChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    protected static ?string $height = '350px';

    protected ?string $defaultFilter = 'all';

    protected bool $reactive = true;

    public function getHeading(): string
    {
        return 'Évolution des Clients';
    }


    protected function getFilters(): ?array
    {
        return [
            '7_days' => '7 derniers jours',
            '30_days' => '30 derniers jours',
            '6_months' => '6 derniers mois',
            '1_year' => '1 dernière année',
            'all' => 'Tous',
        ];
    }

    protected function getData(): array
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        $filter = $this->filter ?? '30_days';

        switch ($filter) {
            case '7_days':
                $dateFrom = now()->subDays(7);
                $groupBy = 'DATE(created_at)';
                $steps = 7;
                $stepUnit = 'days';
                break;
            case '30_days':
                $dateFrom = now()->subDays(30);
                $groupBy = 'DATE(created_at)';
                $steps = 30;
                $stepUnit = 'days';
                break;
            case '6_months':
                $dateFrom = now()->subMonths(6);
                $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
                $steps = 6;
                $stepUnit = 'months';
                break;
            case '1_year':
                $dateFrom = now()->subYear();
                $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
                $steps = 12;
                $stepUnit = 'months';
                break;
            case 'all':
            default:
                $dateFrom = null;
                $groupBy = 'YEAR(created_at)';
                $steps = 5; // Last 5 years
                $stepUnit = 'years';
                break;
        }

        $queryBuilder = $query->select([
            DB::raw("{$groupBy} as period_key"),
            DB::raw('COUNT(*) as count'),
            DB::raw('MIN(created_at) as period_start')
        ]);

        if ($dateFrom) {
            $queryBuilder->where('created_at', '>=', $dateFrom);
        }

        $data = $queryBuilder
            ->groupBy('period_key')
            ->orderBy('period_key')
            ->get()
            ->keyBy('period_key')
            ->toArray();

        // Generate labels and data points
        $labels = [];
        $counts = [];

        if ($filter === 'all') {
            // For all, get distinct years and fill
            $years = Client::query()->selectRaw('DISTINCT YEAR(created_at) as year')
                ->orderBy('year')
                ->pluck('year')
                ->toArray();

            foreach ($years as $year) {
                $labels[] = (string) $year;
                $counts[] = $data[$year]['count'] ?? 0;
            }
        } else {
            for ($i = $steps - 1; $i >= 0; $i--) {
                $periodDate = now()->sub($stepUnit, $i);

                if ($stepUnit === 'days') {
                    $periodKey = $periodDate->format('Y-m-d');
                    $label = $periodDate->format('d/m');
                } elseif ($stepUnit === 'months') {
                    $periodKey = $periodDate->format('Y-m');
                    $label = $periodDate->format('M y');
                } elseif ($stepUnit === 'years') {
                    $periodKey = $periodDate->format('Y');
                    $label = $periodKey;
                } else {
                    $periodKey = $periodDate->format('YW');
                    $label = 'S' . $periodDate->format('W');
                }

                $labels[] = $label;
                $counts[] = $data[$periodKey]['count'] ?? 0;
            }
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
            'labels' => $labels,
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