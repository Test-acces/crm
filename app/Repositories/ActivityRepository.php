<?php

namespace App\Repositories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function all(): Collection
    {
        return Activity::all();
    }

    public function find(int $id): ?Activity
    {
        return Activity::find($id);
    }

    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $activity = Activity::find($id);
        return $activity ? $activity->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $activity = Activity::find($id);
        return $activity ? $activity->delete() : false;
    }

    public function getByClientId(int $clientId): Collection
    {
        return Activity::where('client_id', $clientId)->get();
    }

    public function getRecentActivities(int $days = 7): Collection
    {
        return Activity::where('date', '>=', now()->subDays($days))->get();
    }

    public function getByType(string $type): Collection
    {
        return Activity::where('type', $type)->get();
    }
}