<?php

namespace App\Filament\Forms;

use Filament\Forms;

class TaskForm
{
    public static function schema(): array
    {
        $user = auth()->user();

        return [
            Forms\Components\Select::make('client_id')
                ->relationship('client', 'name', function ($query) use ($user) {
                    if ($user && !$user->canSeeAllClients()) {
                        $query->where('user_id', $user->id);
                    }
                })
                ->required(),
            Forms\Components\Select::make('contact_id')
                ->relationship('contact', 'name', function ($query) use ($user) {
                    if ($user && !$user->canSeeAllClients()) {
                        $query->whereHas('client', function ($clientQuery) use ($user) {
                            $clientQuery->where('user_id', $user->id);
                        });
                    }
                })
                ->nullable(),
            Forms\Components\TextInput::make('title')
                ->required(),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Select::make('status')
                ->options(\App\Models\TaskStatus::options())
                ->default('pending')
                ->required(),
            Forms\Components\Select::make('priority')
                ->options(\App\Models\TaskPriority::options())
                ->default('medium')
                ->required(),
            Forms\Components\DatePicker::make('due_date'),
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->nullable()
                ->visible(fn () => auth()->user()->canManageUsers() || auth()->user()->isAdmin()),
        ];
    }
}