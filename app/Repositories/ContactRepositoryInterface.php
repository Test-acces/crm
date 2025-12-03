<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Contact;
    public function create(array $data): Contact;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByClientId(int $clientId): Collection;
}