<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any clients.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view clients
    }

    /**
     * Determine whether the user can view the client.
     */
    public function view(User $user, $model): bool
    {
        $client = $model;

        // Admins and managers can see all clients
        if ($user->canSeeAllClients()) {
            return true;
        }

        // Commercial users can only see their assigned clients
        return $user->hasRole('commercial') && $client->user_id === $user->id;
    }

    /**
     * Determine whether the user can create clients.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('commercial');
    }

    /**
     * Determine whether the user can update the client.
     */
    public function update(User $user, $model): bool
    {
        $client = $model;

        // Admins and managers can update all clients
        if ($user->canSeeAllClients()) {
            return true;
        }

        // Commercial users can only update their assigned clients
        return $user->hasRole('commercial') && $client->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the client.
     */
    public function delete(User $user, $model): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can activate/deactivate the client.
     */
    public function toggleStatus(User $user, $model): bool
    {
        return $this->update($user, $model);
    }
}