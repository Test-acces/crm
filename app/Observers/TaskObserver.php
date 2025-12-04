<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Activity;
use App\Events\TaskUpdated;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    public function created(Task $task): void
    {
        // Log activity
        Activity::log([
            'task_id' => $task->id,
            'client_id' => $task->client_id,
            'user_id' => Auth::id(),
            'type' => Activity::TYPE_TASK_CREATED,
            'description' => "Tâche créée: {$task->title}",
            'date' => now(),
        ]);
    }

    public function updating(Task $task): void
    {
        if ($task->isDirty('status') && $task->status->value === 'completed') {
            // Log activity for completion
            Activity::log([
                'task_id' => $task->id,
                'client_id' => $task->client_id,
                'user_id' => Auth::id(),
                'type' => Activity::TYPE_TASK_UPDATED,
                'description' => "Tâche marquée comme terminée: {$task->title}",
                'date' => now(),
            ]);
        }
    }

    public function updated(Task $task): void
    {
        TaskUpdated::dispatch($task);
    }
}