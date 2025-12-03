<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Models\User;
use App\Notifications\TaskCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

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

        // Send notification to assigned user if exists
        if ($event->task->user_id) {
            Notification::send($event->task->user, new TaskCreatedNotification($event->task));
        } else {
            // Notify all users about unassigned task
            $users = User::all();
            foreach ($users as $user) {
                Notification::send($user, new TaskCreatedNotification($event->task, true));
            }
        }
    }
}