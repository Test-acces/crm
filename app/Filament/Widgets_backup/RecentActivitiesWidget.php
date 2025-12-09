<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Activity;

class RecentActivitiesWidget extends Widget
{
    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 1;

    protected static ?string $height = '400px';

    protected string $view = 'filament.widgets.recent-activities';

    public function getRecentActivities()
    {
        $user = auth()->user();
        $query = Activity::query()->with(['client', 'contact', 'task', 'user']);

        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', function ($clientQuery) use ($user) {
                $clientQuery->where('user_id', $user->id);
            });
        }

        return $query->orderBy('date', 'desc')->limit(6)->get();
    }

    protected function getActivityTypeLabel($type): string
    {
        return match($type) {
            'call' => 'Appel',
            'email' => 'Email',
            'meeting' => 'Réunion',
            'note' => 'Note',
            'task_created' => 'Tâche créée',
            'task_updated' => 'Tâche mise à jour',
            default => 'Activité'
        };
    }

    protected function getActivityIcon($type): string
    {
        return match($type) {
            'call' => '📞',
            'email' => '📧',
            'meeting' => '👥',
            'note' => '📝',
            'task_created' => '✅',
            'task_updated' => '🔄',
            default => '📋'
        };
    }

    protected function getActivityColor($type): string
    {
        return match($type) {
            'call' => 'blue',
            'email' => 'green',
            'meeting' => 'purple',
            'note' => 'gray',
            'task_created' => 'emerald',
            'task_updated' => 'orange',
            default => 'gray'
        };
    }
}