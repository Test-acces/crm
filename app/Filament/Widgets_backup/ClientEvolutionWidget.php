<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Client;

class ClientEvolutionWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    protected static ?string $height = '400px';

    protected static ?string $heading = 'Évolution des Clients';

    public function table(Table $table): Table
    {
        return $table
            ->query(Client::query()->whereRaw('1 = 0')) // Empty query
            ->columns([
                Tables\Columns\TextColumn::make('custom_content')
                    ->label('')
                    ->formatStateUsing(function () {
                        return view('filament.widgets.client-evolution-chart', [
                            'clientEvolutionData' => $this->getClientEvolutionData(),
                        ]);
                    })
                    ->html(),
            ])
            ->paginated(false);
    }

    public function getClientEvolutionData()
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        // Utiliser une requête groupée plus efficace
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        $clientCounts = $query
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->pluck('count', 'date')
            ->toArray();

        // Générer les données pour les 30 derniers jours
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $clientCounts[$date] ?? 0;
            $data[] = [
                'date' => $date,
                'clients' => $count,
                'formatted_date' => now()->subDays($i)->format('M j'),
            ];
        }

        return $data;
    }

    public function getTotalClientsThisMonth()
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
    }

    public function getTotalClientsLastMonth()
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        return $query->whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year)
                    ->count();
    }

    public function getTotalClients()
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        return $query->count();
    }

    public function getAverageClientsPerMonth()
    {
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        $oldestClient = $query->orderBy('created_at', 'asc')->first();
        if (!$oldestClient) {
            return 0;
        }

        $monthsDiff = now()->diffInMonths($oldestClient->created_at) + 1;
        $totalClients = $query->count();

        return $totalClients / $monthsDiff;
    }
}