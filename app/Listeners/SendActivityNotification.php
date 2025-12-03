<?php

namespace App\Listeners;

use App\Events\ActivityCreated;
use Illuminate\Support\Facades\Log;

class SendActivityNotification
{
    public function handle(ActivityCreated $event): void
    {
        // Log the activity creation
        Log::info('New activity logged', [
            'activity_id' => $event->activity->id,
            'activity_type' => $event->activity->type,
            'activity_description' => $event->activity->description,
            'client_id' => $event->activity->client_id,
            'contact_id' => $event->activity->contact_id,
            'task_id' => $event->activity->task_id,
            'user_id' => $event->activity->user_id,
            'date' => $event->activity->date,
            'created_at' => $event->activity->created_at,
        ]);

        // Here you could send notifications, emails, etc.
        // For example:
        // Notification::send($relevantUsers, new ActivityLoggedNotification($event->activity));
    }
}