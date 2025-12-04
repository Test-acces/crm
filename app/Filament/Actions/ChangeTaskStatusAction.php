<?php

namespace App\Filament\Actions;

use App\Models\Task;
use App\Models\TaskStatus;
use Filament\Forms\Components\Select;

class ChangeTaskStatusAction extends BaseAction
{
    public static function getDefaultName(): ?string
    {
        return 'change_status';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Change Status')
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->form([
                Select::make('status')
                    ->label('New Status')
                    ->options(TaskStatus::options())
                    ->required()
                    ->default(fn (Task $record) => $record->status->value),
            ])
            ->action(function (Task $record, array $data) {
                $oldStatus = $record->status;
                $newStatus = TaskStatus::from($data['status']);

                // Update the task status
                $record->update(['status' => $newStatus]);

                // Log activity
                $record->activities()->create([
                    'client_id' => $record->client_id,
                    'contact_id' => $record->contact_id,
                    'type' => 'task_updated',
                    'description' => "Status changed from {$oldStatus->label()} to {$newStatus->label()}",
                    'date' => now(),
                    'user_id' => auth()->id(),
                ]);

                $this->sendSuccessNotification(
                    "Task '{$record->title}' status changed from {$oldStatus->label()} to {$newStatus->label()}"
                );
            });
    }
}