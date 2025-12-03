<?php

namespace App\Filament\Actions;

use App\Models\Client;
use App\Models\ClientStatus;

class ToggleClientStatusAction extends BaseAction
{
    public static function getDefaultName(): ?string
    {
        return 'toggle_status';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (Client $record) => $record->is_active ? 'Deactivate' : 'Activate')
            ->icon(fn (Client $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
            ->color(fn (Client $record) => $record->is_active ? 'danger' : 'success')
            ->requiresConfirmation()
            ->modalHeading(fn (Client $record) => $record->is_active ? 'Deactivate Client' : 'Activate Client')
            ->modalDescription(fn (Client $record) => $record->is_active
                ? 'Are you sure you want to deactivate this client? They will not be able to access the system.'
                : 'Are you sure you want to activate this client? They will be able to access the system again.'
            )
            ->action(function (Client $record) {
                $oldStatus = $record->status;
                $newStatus = $record->is_active
                    ? ClientStatus::INACTIVE
                    : ClientStatus::ACTIVE;

                $record->update(['status' => $newStatus]);

                // Log activity
                $record->activities()->create([
                    'type' => 'note',
                    'description' => "Client status changed from {$oldStatus->label()} to {$newStatus->label()}",
                    'date' => now(),
                    'user_id' => auth()->id(),
                ]);

                $this->sendSuccessNotification(
                    "Client '{$record->name}' has been " . ($record->is_active ? 'activated' : 'deactivated')
                );
            })
            ->visible(fn (Client $record) => auth()->user()->can('toggleStatus', $record));
    }
}