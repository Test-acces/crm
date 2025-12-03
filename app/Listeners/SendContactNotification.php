<?php

namespace App\Listeners;

use App\Events\ContactCreated;
use Illuminate\Support\Facades\Log;

class SendContactNotification
{
    public function handle(ContactCreated $event): void
    {
        // Log the contact creation
        Log::info('New contact created', [
            'contact_id' => $event->contact->id,
            'contact_name' => $event->contact->name,
            'client_id' => $event->contact->client_id,
            'created_at' => $event->contact->created_at,
        ]);

        // Here you could send notifications, emails, etc.
        // For example:
        // Mail::to($event->contact->email)->send(new WelcomeContactEmail($event->contact));
        // Notification::send($users, new ContactCreatedNotification($event->contact));
    }
}