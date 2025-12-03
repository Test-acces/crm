<?php

namespace App\Repositories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

interface ActivityRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Activity;
    public function create(array $data): Activity;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByClientId(int $clientId): Collection;
    public function getRecentActivities(int $days = 7): Collection;
    public function getByType(string $type): Collection;
}