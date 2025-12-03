<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\ClientSchema;
use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema(ClientSchema::schema());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}