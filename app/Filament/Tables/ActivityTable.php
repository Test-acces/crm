<?php

namespace App\Filament\Tables;

use App\Models\ActivityType;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityTable extends BaseTable
{
    public static function configure(Table $table, bool $readOnly = true): Table
    {
        $table = parent::applyCommonConfiguration($table)
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (ActivityType $state): string => $state->label())
                    ->color(fn (ActivityType $state): string => $state->color()),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->placeholder('System'),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('contact.name')
                    ->label('Contact')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('task.title')
                    ->label('Task')
                    ->placeholder('N/A'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(ActivityType::options())
                    ->placeholder('All Types'),
            ]);

        if (!$readOnly) {
            $table->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        }

        return $table;
    }
}