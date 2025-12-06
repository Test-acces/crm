<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecentActivitiesStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $height = '350px';

    protected int | string | array $columnSpan = 1; // Takes 1/3 of the width

    protected function getStats(): array
    {
        $user = auth()->user();
        $query = Activity::query();

        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', function ($clientQuery) use ($user) {
                $clientQuery->where('user_id', $user->id);
            });
        }

        $activities = $query->latest('date')->limit(5)->get();

        $activityList = '';
        foreach ($activities as $activity) {
            $typeLabel = match($activity->type?->value ?? $activity->type) {
                'call' => '📞',
                'email' => '📧',
                'meeting' => '👥',
                'note' => '📝',
                'task_created' => '✅',
                'task_updated' => '🔄',
                default => '📋'
            };

            $description = $activity->client ? $activity->client->name : 'Client inconnu';
            if ($activity->task) {
                $description .= ' - ' . $activity->task->title;
            }

            $activityList .= "{$typeLabel} {$description} ({$activity->date->format('d/m/y')})\n";
        }

        if (empty($activityList)) {
            $activityList = 'Aucune activité récente';
        }

        return [
            Stat::make('Activités Récentes', $activities->count())
                ->description($activityList)
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}