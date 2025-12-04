<?php

namespace App\Observers;

use App\Models\Contact;
use App\Models\Task;
use App\Events\ContactCreated;
use Illuminate\Support\Facades\Auth;

class ContactObserver
{
    public function created(Contact $contact): void
    {
        // Create automatic task to call the contact
        Task::create([
            'client_id' => $contact->client_id,
            'contact_id' => $contact->id,
            'title' => 'Appeler ce contact',
            'description' => "Contacter {$contact->name} pour établir la relation.",
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => now()->addDay(),
            'user_id' => Auth::id(),
        ]);

        ContactCreated::dispatch($contact);
    }
}