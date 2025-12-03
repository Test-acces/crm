<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(): Collection
    {
        return Task::all();
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $task = Task::find($id);
        return $task ? $task->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $task = Task::find($id);
        return $task ? $task->delete() : false;
    }

    public function getByClientId(int $clientId): Collection
    {
        return Task::where('client_id', $clientId)->get();
    }

    public function getByStatus(string $status): Collection
    {
        return Task::where('status', $status)->get();
    }

    public function getPendingTasks(): Collection
    {
        return $this->getByStatus('pending');
    }

    public function getInProgressTasks(): Collection
    {
        return $this->getByStatus('in_progress');
    }

    public function getCompletedTasks(): Collection
    {
        return $this->getByStatus('completed');
    }
}