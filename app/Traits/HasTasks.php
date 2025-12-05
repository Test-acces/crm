<?php

namespace App\Traits;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Trait for models that have tasks relationship
 * Provides common methods for task counting and management
 */
trait HasTasks
{
    /**
     * Get tasks relationship
     */
    abstract public function tasks(): HasMany;

    /**
     * Get total tasks count
     */
    public function getTotalTasksCount(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Get completed tasks count
     */
    public function getCompletedTasksCount(): int
    {
        return $this->tasks()->where('status', TaskStatus::COMPLETED->value)->count();
    }

    /**
     * Get pending tasks count
     */
    public function getPendingTasksCount(): int
    {
        return $this->tasks()->where('status', TaskStatus::PENDING->value)->count();
    }

    /**
     * Get in progress tasks count
     */
    public function getInProgressTasksCount(): int
    {
        return $this->tasks()->where('status', TaskStatus::IN_PROGRESS->value)->count();
    }

    /**
     * Get active tasks count (pending + in progress)
     */
    public function getActiveTasksCount(): int
    {
        return $this->tasks()
            ->whereIn('status', [TaskStatus::PENDING->value, TaskStatus::IN_PROGRESS->value])
            ->count();
    }

    /**
     * Get recent activities from tasks
     */
    public function getRecentActivities(int $limit = 5): Collection
    {
        return $this->tasks()
            ->join('activities', 'tasks.id', '=', 'activities.task_id')
            ->select('activities.*')
            ->orderBy('activities.date', 'desc')
            ->limit($limit)
            ->get();
    }
}