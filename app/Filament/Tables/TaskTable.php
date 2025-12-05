<?php

namespace App\Filament\Tables;

use App\Filament\Actions\ChangeTaskStatusAction;
use App\Models\Task;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class TaskTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(50)->tooltip(fn ($record) => $record->description),
                Tables\Columns\TextColumn::make('client.name')->label('Client'),
                Tables\Columns\TextColumn::make('contact.name')->label('Contact'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (TaskStatus $state): string => $state->label())
                    ->color(fn (TaskStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->formatStateUsing(fn (TaskPriority $state): string => $state->label())
                    ->color(fn (TaskPriority $state): string => $state->color()),
                Tables\Columns\TextColumn::make('due_date')->date(),
                Tables\Columns\TextColumn::make('user.name')->label('Assigned User'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatus::options()),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(TaskPriority::options()),
            ])
            ->actions([
                ChangeTaskStatusAction::make()
                    ->visible(fn (Task $record) => auth()->user()->can('changeStatus', $record)),
                Actions\EditAction::make()
                    ->visible(fn (Task $record) => auth()->user()->can('update', $record)),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}