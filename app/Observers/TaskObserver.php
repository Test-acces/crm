<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Activity;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        // Log activity when task is created
        Activity::create([
            'client_id' => $task->client_id,
            'contact_id' => $task->contact_id,
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'type' => 'task_created',
            'description' => "Task '{$task->title}' was created",
            'date' => now(),
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        // Only log if important fields changed
        $importantFields = ['status', 'priority', 'user_id', 'due_date'];

        $changes = array_intersect_key($task->getChanges(), array_flip($importantFields));

        if (!empty($changes)) {
            $description = $this->buildUpdateDescription($task, $changes);

            Activity::create([
                'client_id' => $task->client_id,
                'contact_id' => $task->contact_id,
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'type' => 'task_updated',
                'description' => $description,
                'date' => now(),
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        // Log activity when task is deleted (if not force deleted)
        if (!$task->isForceDeleting()) {
            Activity::create([
                'client_id' => $task->client_id,
                'contact_id' => $task->contact_id,
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'type' => 'note',
                'description' => "Task '{$task->title}' was deleted",
                'date' => now(),
            ]);
        }
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        Activity::create([
            'client_id' => $task->client_id,
            'contact_id' => $task->contact_id,
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'type' => 'note',
            'description' => "Task '{$task->title}' was restored",
            'date' => now(),
        ]);
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        // Log permanent deletion
        Activity::create([
            'client_id' => $task->client_id,
            'contact_id' => $task->contact_id,
            'user_id' => auth()->id(),
            'type' => 'note',
            'description' => "Task '{$task->title}' was permanently deleted",
            'date' => now(),
        ]);
    }

    /**
     * Build description for task updates.
     */
    private function buildUpdateDescription(Task $task, array $changes): string
    {
        $descriptions = [];

        foreach ($changes as $field => $newValue) {
            $oldValue = $task->getOriginal($field);

            switch ($field) {
                case 'status':
                    $descriptions[] = "status changed from {$oldValue} to {$newValue}";
                    break;
                case 'priority':
                    $descriptions[] = "priority changed from {$oldValue} to {$newValue}";
                    break;
                case 'user_id':
                    $oldUser = $oldValue ? \App\Models\User::find($oldValue)?->name : 'unassigned';
                    $newUser = $newValue ? \App\Models\User::find($newValue)?->name : 'unassigned';
                    $descriptions[] = "assignment changed from {$oldUser} to {$newUser}";
                    break;
                case 'due_date':
                    $oldDate = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('M j, Y') : 'no date';
                    $newDate = $newValue ? \Carbon\Carbon::parse($newValue)->format('M j, Y') : 'no date';
                    $descriptions[] = "due date changed from {$oldDate} to {$newDate}";
                    break;
            }
        }

        return "Task '{$task->title}': " . implode(', ', $descriptions);
    }
}