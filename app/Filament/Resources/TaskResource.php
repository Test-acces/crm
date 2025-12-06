<?php

namespace App\Filament\Resources;

use App\Filament\Forms\TaskForm;
use App\Filament\Pages as Pages;
use App\Filament\Tables\TaskTable;
use App\Models\Task;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'CRM Management';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::overdue()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(TaskForm::schema());
    }

    public static function table(Table $table): Table
    {
        return TaskTable::configure($table);
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user && !$user->isAdmin()) {
            // Non-admins can only see tasks assigned to them or related to their clients
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('client', function ($clientQuery) use ($user) {
                      if ($user->hasRole('commercial')) {
                          $clientQuery->where('user_id', $user->id);
                      }
                  });
            });
        }

        return $query;
    }
}