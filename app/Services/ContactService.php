<?php

namespace App\Services;

use App\Events\ContactCreated;
use App\Models\Contact;
use App\Repositories\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Event;

class ContactService
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->contactRepository->all();
    }

    public function findById(int $id): ?Contact
    {
        return $this->contactRepository->find($id);
    }

    public function create(array $data): Contact
    {
        $this->validateContactData($data);
        $contact = $this->contactRepository->create($data);

        // Dispatch event for notifications
        Event::dispatch(new ContactCreated($contact));

        return $contact;
    }

    public function update(Contact $contact, array $data): Contact
    {
        $this->validateContactData($data, $contact->id);
        $this->contactRepository->update($contact->id, $data);
        return $contact->fresh();
    }

    public function delete(Contact $contact): bool
    {
        return $this->contactRepository->delete($contact->id);
    }

    public function getByClientId(int $clientId): Collection
    {
        return $this->contactRepository->getByClientId($clientId);
    }

    private function validateContactData(array $data, ?int $excludeId = null): void
    {
        if (isset($data['email'])) {
            $query = Contact::where('email', $data['email']);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if ($query->exists()) {
                throw new \Exception('Email already exists');
            }
        }
    }
}