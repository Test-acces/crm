<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Task;
    public function create(array $data): Task;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByClientId(int $clientId): Collection;
    public function getByStatus(string $status): Collection;
    public function getPendingTasks(): Collection;
    public function getInProgressTasks(): Collection;
    public function getCompletedTasks(): Collection;
}