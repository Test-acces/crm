<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivitiesWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Activités Récentes';

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $query = Activity::query()->with(['client', 'contact', 'task', 'user']);

        if ($user && !$user->canSeeAllClients()) {
            $query->whereHas('client', function ($clientQuery) use ($user) {
                $clientQuery->where('user_id', $user->id);
            });
        }

        return $table
            ->query($query->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match($state) {
                        'call' => 'info',
                        'email' => 'success',
                        'meeting' => 'warning',
                        'note' => 'gray',
                        'task_created' => 'primary',
                        'task_updated' => 'secondary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur'),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc');
    }
}