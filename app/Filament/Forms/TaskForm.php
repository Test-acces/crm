<?php

namespace App\Filament\Forms;

use Filament\Forms;

class TaskForm
{
    public static function schema(): array
    {
        return [
            Forms\Components\Select::make('client_id')
                ->relationship('client', 'name')
                ->required(),
            Forms\Components\Select::make('contact_id')
                ->relationship('contact', 'name')
                ->nullable(),
            Forms\Components\TextInput::make('title')
                ->required(),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                ])
                ->default('pending')
                ->required(),
            Forms\Components\Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ])
                ->default('medium')
                ->required(),
            Forms\Components\DatePicker::make('due_date'),
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->nullable(),
        ];
    }
}