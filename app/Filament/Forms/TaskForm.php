<?php

namespace App\Filament\Forms;

use Filament\Schemas;

class TaskForm
{
    public static function schema(): array
    {
        return [
            Schemas\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->required(),
            Schemas\Components\Select::make('contact_id')
                ->relationship('contact', 'name')
                ->nullable(),
            Schemas\Components\TextInput::make('title')
                ->required(),
            Schemas\Components\Textarea::make('description'),
            Schemas\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                ])
                ->default('pending')
                ->required(),
            Schemas\Components\Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ])
                ->default('medium')
                ->required(),
            Schemas\Components\DatePicker::make('due_date'),
            Schemas\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->nullable(),
        ];
    }
}