<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy extends BasePolicy
{
    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, $model): bool
    {
        // Allow if user is assigned to the task or is admin
        return $model->canBeEditedBy($user);
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, $model): bool
    {
        // Only assigned user or admin can delete
        return $model->canBeEditedBy($user);
    }

    /**
     * Determine whether the user can change task status.
     */
    public function changeStatus(User $user, $model): bool
    {
        return $model->canBeEditedBy($user);
    }

    /**
     * Determine whether the user can assign tasks.
     */
    public function assign(User $user, $model): bool
    {
        return true; // All authenticated users can assign tasks
    }

    /**
     * Determine whether the user can view overdue tasks.
     */
    public function viewOverdue(User $user): bool
    {
        return true; // All authenticated users can view overdue tasks
    }
}