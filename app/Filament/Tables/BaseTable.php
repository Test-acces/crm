<?php

namespace App\Filament\Tables;

use Filament\Tables\Table;

abstract class BaseTable
{
    /**
     * Configure common table settings
     */
    protected static function applyCommonConfiguration(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s'); // Auto-refresh every 30 seconds
    }

    /**
     * Get common filters for all tables
     */
    protected static function getCommonFilters(): array
    {
        return [
            // Common filters can be added here
        ];
    }

    /**
     * Get common actions for all tables
     */
    protected static function getCommonActions(): array
    {
        return [
            // Common actions can be added here
        ];
    }

    /**
     * Get common bulk actions for all tables
     */
    protected static function getCommonBulkActions(): array
    {
        return [
            // Common bulk actions can be added here
        ];
    }
}