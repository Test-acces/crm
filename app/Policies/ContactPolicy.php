<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;

class ContactPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the contact.
     */
    public function view(User $user, $model): bool
    {
        $contact = $model;
        // User can view contact if they can view the associated client
        return $user->can('view', $contact->client);
    }

    /**
     * Determine whether the user can create contacts.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('commercial');
    }

    /**
     * Determine whether the user can update the contact.
     */
    public function update(User $user, $model): bool
    {
        $contact = $model;
        // User can update contact if they can update the associated client
        return $user->can('update', $contact->client);
    }

    /**
     * Determine whether the user can delete the contact.
     */
    public function delete(User $user, $model): bool
    {
        $contact = $model;
        // User can delete contact if they can update the associated client
        // and the contact can be safely deleted
        return $user->can('update', $contact->client) && $contact->canBeDeleted();
    }
}