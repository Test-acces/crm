<?php

namespace App\Filament\Resources;

use App\Filament\Forms\TaskForm;
use App\Filament\Pages as Pages;
use App\Filament\Tables\TaskTable;
use App\Models\Task;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';
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
}