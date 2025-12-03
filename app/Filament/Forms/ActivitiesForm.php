<?php

namespace App\Filament\Forms;

use Filament\Schemas;

class ActivitiesForm
{
    public static function schema(): array
    {
        return [
            Schemas\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),
            Schemas\Components\Select::make('type')
                ->options([
                    'call' => 'Call',
                    'email' => 'Email',
                    'meeting' => 'Meeting',
                    'note' => 'Note',
                    'task_created' => 'Task Created',
                    'task_updated' => 'Task Updated',
                ])
                ->required(),
            Schemas\Components\Textarea::make('description'),
            Schemas\Components\DateTimePicker::make('date')
                ->default(now())
                ->required(),
        ];
    }
}