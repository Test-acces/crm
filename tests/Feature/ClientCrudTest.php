<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_model_can_be_created()
    {
        $clientData = [
            'name' => 'Test Client',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'address' => '123 Test St',
        ];

        $client = Client::create($clientData);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertDatabaseHas('clients', $clientData);
    }

    public function test_client_model_can_be_read()
    {
        $client = Client::factory()->create();

        $foundClient = Client::find($client->id);

        $this->assertInstanceOf(Client::class, $foundClient);
        $this->assertEquals($client->name, $foundClient->name);
    }

    public function test_client_model_can_be_updated()
    {
        $client = Client::factory()->create();

        $updatedData = [
            'name' => 'Updated Client',
            'email' => 'updated@example.com',
        ];

        $client->update($updatedData);

        $this->assertDatabaseHas('clients', $updatedData);
    }

    public function test_client_model_can_be_deleted()
    {
        $client = Client::factory()->create();

        $client->delete();

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
