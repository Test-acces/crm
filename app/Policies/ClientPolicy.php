<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy extends BasePolicy
{
    /**
     * Determine whether the user can activate/deactivate the client.
     */
    public function toggleStatus(User $user, Client $client): bool
    {
        return true; // All authenticated users can toggle status
    }
}