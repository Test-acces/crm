<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use Illuminate\Support\Facades\Log;

class SendClientNotification
{
    public function handle(ClientCreated $event): void
    {
        // Log the client creation
        Log::info('New client created', [
            'client_id' => $event->client->id,
            'client_name' => $event->client->name,
            'created_at' => $event->client->created_at,
        ]);

        // Here you could send notifications, emails, etc.
        // For example:
        // Mail::to($event->client->email)->send(new WelcomeEmail($event->client));
        // Notification::send($users, new ClientCreatedNotification($event->client));
    }
}