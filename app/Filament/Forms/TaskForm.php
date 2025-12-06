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
                ->searchable()
                ->preload()
                ->label('Client associé')
                ->placeholder('Sélectionnez un client')
                ->required()
                ->helperText('Le client pour lequel cette tâche est créée.'),
            Forms\Components\Select::make('contact_id')
                ->relationship('contact', 'name', function ($query) use ($user) {
                    if ($user && !$user->canSeeAllClients()) {
                        $query->whereHas('client', function ($clientQuery) use ($user) {
                            $clientQuery->where('user_id', $user->id);
                        });
                    }
                })
                ->searchable()
                ->preload()
                ->label('Contact associé')
                ->placeholder('Sélectionnez un contact (optionnel)')
                ->nullable()
                ->helperText('Le contact principal pour cette tâche.'),
            Forms\Components\TextInput::make('title')
                ->label('Titre de la tâche')
                ->placeholder('Entrez un titre descriptif')
                ->required()
                ->helperText('Le titre doit être clair et concis.'),
            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->placeholder('Décrivez la tâche en détail')
                ->helperText('Fournissez autant de détails que possible.'),
            Forms\Components\Select::make('status')
                ->options(\App\Models\TaskStatus::options())
                ->default('pending')
                ->label('Statut')
                ->required()
                ->helperText('Le statut actuel de la tâche.'),
            Forms\Components\Select::make('priority')
                ->options(\App\Models\TaskPriority::options())
                ->default('medium')
                ->label('Priorité')
                ->required()
                ->helperText('La priorité de la tâche.'),
            Forms\Components\DatePicker::make('due_date')
                ->label('Date d\'échéance')
                ->placeholder('Sélectionnez une date')
                ->helperText('La date limite pour terminer la tâche.'),
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->label('Utilisateur assigné')
                ->placeholder('Sélectionnez un utilisateur')
                ->nullable()
                ->visible(fn () => auth()->user()->canManageUsers() || auth()->user()->isAdmin())
                ->helperText('L\'utilisateur responsable de cette tâche.'),
        ];
    }
}