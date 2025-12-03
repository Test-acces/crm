<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any activities.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view activities
    }

    /**
     * Determine whether the user can view the activity.
     */
    public function view(User $user, Activity $activity): bool
    {
        return true; // All authenticated users can view activities
    }

    /**
     * Determine whether the user can create activities.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create activities
    }

    /**
     * Determine whether the user can update the activity.
     */
    public function update(User $user, Activity $activity): bool
    {
        // Only the user who created the activity or admin can update
        return $activity->user_id === $user->id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the activity.
     */
    public function delete(User $user, Activity $activity): bool
    {
        // Only the user who created the activity or admin can delete
        return $activity->user_id === $user->id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the activity.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return $user->hasRole('admin'); // Only admins can restore
    }

    /**
     * Determine whether the user can permanently delete the activity.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return $user->hasRole('admin'); // Only admins can force delete
    }

    /**
     * Determine whether the user can view activities for a specific client.
     */
    public function viewForClient(User $user, int $clientId): bool
    {
        return true; // All authenticated users can view client activities
    }

    /**
     * Determine whether the user can log activities.
     */
    public function log(User $user): bool
    {
        return true; // All authenticated users can log activities
    }
}