<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OverdueTasksWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Tâches en Retard';

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $query = Task::query()->with(['client', 'contact', 'user']);

        if ($user && !$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      if ($user->hasRole('commercial')) {
                          $clientQuery->where('user_id', $user->id);
                      }
                  });
            });
        }

        return $table
            ->query($query->overdue()->latest('due_date'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact.name')
                    ->label('Contact')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigné à')
                    ->placeholder('Non assigné'),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('due_date', 'asc');
    }
}