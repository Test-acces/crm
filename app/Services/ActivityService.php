<?php

namespace App\Services;

use App\Events\ActivityCreated;
use App\Models\Activity;
use App\Repositories\ActivityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;

class ActivityService
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->activityRepository->all();
    }

    public function findById(int $id): ?Activity
    {
        return $this->activityRepository->find($id);
    }

    public function create(array $data): Activity
    {
        $this->validateActivityData($data);
        $activity = $this->activityRepository->create($data);

        // Dispatch event for notifications
        Event::dispatch(new ActivityCreated($activity));

        return $activity;
    }

    public function update(Activity $activity, array $data): Activity
    {
        $this->validateActivityData($data);
        $this->activityRepository->update($activity->id, $data);
        return $activity->fresh();
    }

    public function delete(Activity $activity): bool
    {
        return $this->activityRepository->delete($activity->id);
    }

    public function getByClientId(int $clientId): Collection
    {
        return $this->activityRepository->getByClientId($clientId);
    }

    public function getRecentActivities(int $days = 7): Collection
    {
        return $this->activityRepository->getRecentActivities($days);
    }

    public function getByType(string $type): Collection
    {
        return $this->activityRepository->getByType($type);
    }

    public function logActivity(int $clientId, string $type, string $description, ?int $contactId = null, ?int $taskId = null, ?int $userId = null): Activity
    {
        return $this->create([
            'client_id' => $clientId,
            'contact_id' => $contactId,
            'task_id' => $taskId,
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'date' => now(),
        ]);
    }

    private function validateActivityData(array $data): void
    {
        // Validation business logic
        if (!isset($data['client_id'])) {
            throw new \Exception('Client ID is required for activity');
        }
    }
}