<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Client;
use App\Models\Task;

class GeneralOverviewWidget extends Widget
{
    protected static ?int $sort = 21;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 2,
        'lg' => 2,
    ];

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.general-overview';

    public function getTotalFleet(): int
    {
        // Total Clients
        $user = auth()->user();
        $query = Client::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        return $query->count();
    }

    public function getTotalRevenue(): int
    {
        // Total Tasks
        $user = auth()->user();
        $query = Task::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      $clientQuery->where('user_id', $user->id);
                  });
            });
        }

        return $query->count();
    }

    public function getRevenueToCollect(): int
    {
        // Pending Tasks
        $user = auth()->user();
        $query = Task::query()->where('status', 'pending');

        if ($user && !$user->canSeeAllClients()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      $clientQuery->where('user_id', $user->id);
                  });
            });
        }

        return $query->count();
    }

    public function getOccupancyRate(): int
    {
        // Completion Rate (% of completed tasks)
        $user = auth()->user();
        $totalQuery = Task::query();
        $completedQuery = Task::query()->where('status', 'completed');

        if ($user && !$user->canSeeAllClients()) {
            $totalQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      $clientQuery->where('user_id', $user->id);
                  });
            });
            $completedQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      $clientQuery->where('user_id', $user->id);
                  });
            });
        }

        $total = $totalQuery->count();
        $completed = $completedQuery->count();

        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    public function getClientsChartData(): array
    {
        $user = auth()->user();
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $query = Client::query()->whereDate('created_at', $date);
            
            if ($user && !$user->canSeeAllClients()) {
                $query->where('user_id', $user->id);
            }
            
            $data[] = $query->count();
        }
        
        return $data;
    }

    public function getTasksChartData(): array
    {
        $user = auth()->user();
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $query = Task::query()->whereDate('created_at', $date);
            
            if ($user && !$user->canSeeAllClients()) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereHas('client', function ($clientQuery) use ($user) {
                          $clientQuery->where('user_id', $user->id);
                      });
                });
            }
            
            $data[] = $query->count();
        }
        
        return $data;
    }

    public function getPendingTasksChartData(): array
    {
        $user = auth()->user();
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $query = Task::query()->where('status', 'pending')
                                  ->whereDate('created_at', $date);
            
            if ($user && !$user->canSeeAllClients()) {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereHas('client', function ($clientQuery) use ($user) {
                          $clientQuery->where('user_id', $user->id);
                      });
                });
            }
            
            $data[] = $query->count();
        }
        
        return $data;
    }

    public function getCompletionRateChartData(): array
    {
        $user = auth()->user();
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $totalQuery = Task::query()->whereDate('created_at', $date);
            $completedQuery = Task::query()->where('status', 'completed')
                                           ->whereDate('created_at', $date);
            
            if ($user && !$user->canSeeAllClients()) {
                $totalQuery->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereHas('client', function ($clientQuery) use ($user) {
                          $clientQuery->where('user_id', $user->id);
                      });
                });
                $completedQuery->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereHas('client', function ($clientQuery) use ($user) {
                          $clientQuery->where('user_id', $user->id);
                      });
                });
            }
            
            $total = $totalQuery->count();
            $completed = $completedQuery->count();
            $data[] = $total > 0 ? round(($completed / $total) * 100) : 0;
        }
        
        return $data;
    }
}
