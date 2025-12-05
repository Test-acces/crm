<?php

namespace App\Filament\Actions;

use App\Models\Activity;
use App\Models\ActivityType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class QuickLogActivityAction extends BaseAction
{
    public static function getDefaultName(): ?string
    {
        return 'log_activity';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Log Activity')
            ->icon('heroicon-o-plus-circle')
            ->color('info')
            ->form([
                Select::make('type')
                    ->label('Activity Type')
                    ->options(ActivityType::options())
                    ->required(),
                TextInput::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(255),
            ])
            ->action(function (array $data, $record) {
                // Determine the related entity based on the context
                $clientId = null;
                $contactId = null;
                $taskId = null;

                if ($record instanceof \App\Models\Client) {
                    $clientId = $record->id;
                } elseif ($record instanceof \App\Models\Contact) {
                    $clientId = $record->client_id;
                    $contactId = $record->id;
                } elseif ($record instanceof \App\Models\Task) {
                    $clientId = $record->client_id;
                    $contactId = $record->contact_id;
                    $taskId = $record->id;
                }

                // Check permissions before creating activity
                if (!auth()->user()->can('logForClient', [Activity::class, $clientId])) {
                    $this->sendErrorNotification('You are not authorized to log activities for this client.');
                    return;
                }

                Activity::create([
                    'client_id' => $clientId,
                    'contact_id' => $contactId,
                    'task_id' => $taskId,
                    'user_id' => auth()->id(),
                    'type' => $data['type'],
                    'description' => $data['description'],
                    'date' => now(),
                ]);

                $this->sendSuccessNotification('The activity has been successfully logged.');
            })
            ->visible(fn ($record) => auth()->user()->can('log', Activity::class));
    }
}