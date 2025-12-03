<?php

namespace App\Filament\Forms;

use App\Models\ActivityType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;

class ActivitiesForm
{
    public static function schema(): array
    {
        return [
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),
            Select::make('type')
                ->options(ActivityType::options())
                ->required(),
            Textarea::make('description'),
            DateTimePicker::make('date')
                ->default(now())
                ->required(),
        ];
    }
}