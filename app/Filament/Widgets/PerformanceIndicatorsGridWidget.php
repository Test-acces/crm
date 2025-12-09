<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Client;
use App\Models\Contact;

class PerformanceIndicatorsGridWidget extends Widget
{
    protected static ?int $sort = 22;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 2,
        'lg' => 2,
    ];

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.performance-indicators';

    // ===== CLIENTS TAB =====
    public function getTotalClients(): int
    {
        $user = auth()->user();
        $query = Client::query();
        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }
        return $query->count();
    }

    public function getActiveClients(): int
    {
        $user = auth()->user();
        $query = Client::query()->where('status', 'active');
        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }
        return $query->count();
    }

    public function getClientsWithTasks(): int
    {
        $user = auth()->user();
        $query = Client::query()->whereHas('tasks');
        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }
        return $query->count();
    }

    // ===== TASKS TAB =====
    public function getTotalTasks(): int
    {
        $user = auth()->user();
        $query = \App\Models\Task::query();
        if ($user && !$user->canSeeAllClients()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', fn($cq) => $cq->where('user_id', $user->id));
            });
        }
        return $query->count();
    }

    public function getInProgressTasks(): int
    {
        $user = auth()->user();
        $query = \App\Models\Task::query()->where('status', 'in_progress');
        if ($user && !$user->canSeeAllClients()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', fn($cq) => $cq->where('user_id', $user->id));
            });
        }
        return $query->count();
    }

    public function getCompletedTasks(): int
    {
        $user = auth()->user();
        $query = \App\Models\Task::query()->where('status', 'completed');
        if ($user && !$user->canSeeAllClients()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', fn($cq) => $cq->where('user_id', $user->id));
            });
        }
        return $query->count();
    }

    // ===== CONTACTS TAB =====
    public function getTotalContacts(): int
    {
        $user = auth()->user();
        $query = Contact::query();
        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', fn($q) => $q->where('user_id', $user->id));
        }
        return $query->count();
    }

    public function getAverageContactsPerClient(): int
    {
        $totalClients = $this->getTotalClients();
        $totalContacts = $this->getTotalContacts();
        return $totalClients > 0 ? round($totalContacts / $totalClients) : 0;
    }

    public function getRecentContacts(): int
    {
        $user = auth()->user();
        $query = Contact::query()->where('created_at', '>=', now()->subDays(30));
        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', fn($q) => $q->where('user_id', $user->id));
        }
        return $query->count();
    }

    // ===== ACTIVITIES TAB =====
    public function getTotalActivities(): int
    {
        $user = auth()->user();
        $query = \App\Models\Activity::query();
        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', fn($q) => $q->where('user_id', $user->id));
        }
        return $query->count();
    }

    public function getRecentActivitiesCount(): int
    {
        $user = auth()->user();
        $query = \App\Models\Activity::query()->where('date', '>=', now()->subDays(7));
        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', fn($q) => $q->where('user_id', $user->id));
        }
        return $query->count();
    }

    public function getTodayActivities(): int
    {
        $user = auth()->user();
        $query = \App\Models\Activity::query()->whereDate('date', today());
        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', fn($q) => $q->where('user_id', $user->id));
        }
        return $query->count();
    }
}
