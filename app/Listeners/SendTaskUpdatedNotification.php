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

        // Notify users who should be aware of this task update
        $this->notifyUsersAboutTaskUpdate($event->task);
    }

    /**
     * Notify appropriate users about task updates based on their permissions.
     */
    private function notifyUsersAboutTaskUpdate($task): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Notify if user is assigned to task, can see all clients, or is commercial responsible for client
            if ($task->user_id === $user->id ||
                $user->canSeeAllClients() ||
                ($user->hasRole('commercial') && $task->client && $task->client->user_id === $user->id)) {
                Notification::send($user, new TaskUpdatedNotification($task));
            }
        }
    }
}