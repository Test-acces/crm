<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Support\Facades\Log;

class SendTaskNotification
{
    public function handle(TaskCreated $event): void
    {
        // Log the task creation
        Log::info('New task created', [
            'task_id' => $event->task->id,
            'task_title' => $event->task->title,
            'client_id' => $event->task->client_id,
            'status' => $event->task->status,
            'priority' => $event->task->priority,
            'due_date' => $event->task->due_date,
            'created_at' => $event->task->created_at,
        ]);

        // Here you could send notifications, emails, etc.
        // For example:
        // Mail::to($assignedUser->email)->send(new TaskAssignedEmail($event->task));
        // Notification::send($users, new TaskCreatedNotification($event->task));
    }
}