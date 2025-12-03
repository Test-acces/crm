<?php

namespace App\Filament\Resources;

use App\Filament\Pages as Pages;
use App\Filament\Tables\ActivityTable;
use App\Models\Activity;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clock';
    }


    public static function table(Table $table): Table
    {
        return ActivityTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}