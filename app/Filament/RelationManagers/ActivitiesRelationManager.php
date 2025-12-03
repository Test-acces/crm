<?php

namespace App\Filament\RelationManagers;

use App\Filament\Forms\ActivitiesForm;
use App\Filament\Tables\ActivitiesTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public function form(Schema $schema): Schema
    {
        return $schema->schema(ActivitiesForm::schema());
    }

    public function table(Table $table): Table
    {
        return ActivitiesTable::configure($table);
    }
}