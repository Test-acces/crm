<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    // No header actions for read-only
}
