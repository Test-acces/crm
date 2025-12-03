<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ?Client;
    public function create(array $data): Client;
    public function update(Client $client, array $data): Client;
    public function delete(Client $client): bool;
    public function getActiveClients(): Collection;
    public function getPaginated(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator;
}