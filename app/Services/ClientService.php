<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->clientRepository->getAll();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->clientRepository->getPaginated($perPage);
    }

    public function findById(int $id): ?Client
    {
        return $this->clientRepository->findById($id);
    }

    public function create(array $data): Client
    {
        // Business logic validation
        $this->validateClientData($data);

        return $this->clientRepository->create($data);
    }

    public function update(Client $client, array $data): Client
    {
        // Business logic validation
        $this->validateClientData($data, $client->id);

        $client = $this->clientRepository->update($client, $data);

        // Additional business logic after update
        // e.g., update related records, send notifications, etc.

        return $client;
    }

    public function delete(Client $client): bool
    {
        // Check if client can be deleted (using trait method)
        if (!$client->canBeDeleted()) {
            throw new \Exception($client->getDeletionErrorMessage());
        }

        return $this->clientRepository->delete($client);
    }

    public function getActiveClients(): Collection
    {
        return $this->clientRepository->getActiveClients();
    }

    private function validateClientData(array $data, ?int $excludeId = null): void
    {
        // Custom business validation
        if (isset($data['email'])) {
            $query = Client::where('email', $data['email']);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if ($query->exists()) {
                throw new \Exception('Email already exists');
            }
        }
    }
}