<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Activity;

class SidebarActivitiesWidget extends Widget
{
    protected static ?int $sort = 20;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'lg' => 1,
    ];

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.sidebar-activities';

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
}
