<?php

namespace App\Services;

use App\Events\TaskCreated;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->taskRepository->all();
    }

    public function findById(int $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    public function create(array $data): Task
    {
        $this->validateTaskData($data);
        $task = $this->taskRepository->create($data);

        // Dispatch event for notifications
        Event::dispatch(new TaskCreated($task));

        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $this->validateTaskData($data, $task->id);
        $this->taskRepository->update($task->id, $data);
        return $task->fresh();
    }

    public function delete(Task $task): bool
    {
        return $this->taskRepository->delete($task->id);
    }

    public function getByClientId(int $clientId): Collection
    {
        return $this->taskRepository->getByClientId($clientId);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->taskRepository->getByStatus($status);
    }

    public function getPendingTasks(): Collection
    {
        return $this->taskRepository->getPendingTasks();
    }

    public function getInProgressTasks(): Collection
    {
        return $this->taskRepository->getInProgressTasks();
    }

    public function getCompletedTasks(): Collection
    {
        return $this->taskRepository->getCompletedTasks();
    }

    /**
     * Change task status to pending
     */
    public function markAsPending(Task $task): Task
    {
        return $this->updateStatus($task, \App\Models\TaskStatus::PENDING);
    }

    /**
     * Change task status to in progress
     */
    public function markAsInProgress(Task $task): Task
    {
        return $this->updateStatus($task, \App\Models\TaskStatus::IN_PROGRESS);
    }

    /**
     * Change task status to completed
     */
    public function markAsCompleted(Task $task): Task
    {
        return $this->updateStatus($task, \App\Models\TaskStatus::COMPLETED);
    }

    /**
     * Assign task to a user
     */
    public function assignToUser(Task $task, int $userId): Task
    {
        return $this->update($task, ['user_id' => $userId]);
    }

    /**
     * Set task priority
     */
    public function setPriority(Task $task, string $priority): Task
    {
        return $this->update($task, ['priority' => $priority]);
    }

    /**
     * Update task due date
     */
    public function updateDueDate(Task $task, ?\Carbon\Carbon $dueDate): Task
    {
        return $this->update($task, ['due_date' => $dueDate]);
    }

    /**
     * Update task status with business logic
     */
    private function updateStatus(Task $task, \App\Models\TaskStatus $status): Task
    {
        // Business logic: prevent certain status changes
        if ($status === \App\Models\TaskStatus::COMPLETED && $task->due_date && $task->due_date->isPast()) {
            // Could add notification for overdue completion
        }

        return $this->update($task, ['status' => $status->value]);
    }

    private function validateTaskData(array $data, ?int $excludeId = null): void
    {
        // Validation business logic
        if (isset($data['due_date']) && $data['due_date'] < now()->toDateString()) {
            throw new \Exception('Due date cannot be in the past');
        }
    }
}