<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;

abstract class BaseAction extends Action
{
    protected function getDefaultModalHeading(): ?string
    {
        return 'Confirm Action';
    }

    protected function getDefaultModalDescription(): ?string
    {
        return 'Are you sure you want to perform this action?';
    }

    public function sendSuccessNotification(string $message = 'Action completed successfully'): static
    {
        Notification::make()
            ->title('Success')
            ->body($message)
            ->success()
            ->send();

        return $this;
    }

    protected function sendErrorNotification(string $message = 'An error occurred'): void
    {
        Notification::make()
            ->title('Error')
            ->body($message)
            ->danger()
            ->send();
    }

    protected function sendWarningNotification(string $message = 'Warning'): void
    {
        Notification::make()
            ->title('Warning')
            ->body($message)
            ->warning()
            ->send();
    }
}