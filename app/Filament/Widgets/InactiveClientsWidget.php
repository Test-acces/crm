<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class InactiveClientsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Clients Inactifs (30+ jours)';

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $query = Client::query()->with(['user', 'contacts', 'tasks']);

        if ($user && !$user->canSeeAllClients()) {
            $query->where('user_id', $user->id);
        }

        return $table
            ->query(
                $query->whereDoesntHave('activities', function ($activityQuery) {
                    $activityQuery->where('date', '>=', now()->subDays(30));
                })
                ->where('status', 'active')
                ->orderBy('updated_at', 'asc')
                ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->tooltip(fn ($record) => $record->name),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->email),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('contacts_count')
                    ->label('Contacts')
                    ->counts('contacts')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tasks_count')
                    ->label('Tâches')
                    ->counts('tasks')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_activity')
                    ->label('Dernière activité')
                    ->state(function ($record) {
                        $lastActivity = $record->activities()->latest('date')->first();
                        return $lastActivity ? $lastActivity->date->diffForHumans() : 'Aucune';
                    })
                    ->color(function ($record) {
                        $lastActivity = $record->activities()->latest('date')->first();
                        return $lastActivity ? 'warning' : 'danger';
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Commercial')
                    ->placeholder('Non assigné'),
            ])
            ->defaultSort('updated_at', 'asc');
    }
}