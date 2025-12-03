<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\ClientStatus;
use Illuminate\Database\Eloquent\Collection;

class ClientRepository implements ClientRepositoryInterface
{
    public function getAll(): Collection
    {
        return Client::all();
    }

    public function findById(int $id): ?Client
    {
        return Client::find($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->fresh();
    }

    public function delete(Client $client): bool
    {
        return $client->delete();
    }

    public function getActiveClients(): Collection
    {
        return Client::where('status', ClientStatus::ACTIVE->value)->get();
    }

    public function getPaginated(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Client::paginate($perPage);
    }
}