<?php

namespace App\Observers;

use App\Models\Client;
use App\Events\ClientCreated;
use Illuminate\Support\Facades\Auth;

class ClientObserver
{
    public function created(Client $client): void
    {
        ClientCreated::dispatch($client);
    }

    public function updating(Client $client): void
    {
        if ($client->isDirty('status')) {
            $oldStatus = $client->getOriginal('status');
            $newStatus = $client->status;

            $note = "Statut changé de {$oldStatus} à {$newStatus} le " . now()->format('Y-m-d H:i:s');

            $existingNotes = $client->notes ?? '';
            $client->notes = $existingNotes . ($existingNotes ? "\n\n" : '') . $note;
        }
    }
}