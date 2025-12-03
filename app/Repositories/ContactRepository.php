<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository implements ContactRepositoryInterface
{
    public function all(): Collection
    {
        return Contact::all();
    }

    public function find(int $id): ?Contact
    {
        return Contact::find($id);
    }

    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $contact = Contact::find($id);
        return $contact ? $contact->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $contact = Contact::find($id);
        return $contact ? $contact->delete() : false;
    }

    public function getByClientId(int $clientId): Collection
    {
        return Contact::where('client_id', $clientId)->get();
    }
}