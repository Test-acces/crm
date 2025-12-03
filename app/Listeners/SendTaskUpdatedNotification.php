<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Models\User;
use App\Notifications\TaskUpdatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendTaskUpdatedNotification
{
    public function handle(TaskUpdated $event): void
    {
        // Log the task update
        Log::info('Task updated', [
            'task_id' => $event->task->id,
            'task_title' => $event->task->title,
            'client_id' => $event->task->client_id,
            'status' => $event->task->status,
            'priority' => $event->task->priority,
            'due_date' => $event->task->due_date,
            'updated_at' => $event->task->updated_at,
        ]);

        // Notify all users about the task update
        $users = User::all();
        foreach ($users as $user) {
            Notification::send($user, new TaskUpdatedNotification($event->task));
        }
    }
}