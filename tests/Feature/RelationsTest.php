<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_has_many_contacts()
    {
        $client = Client::factory()->create();
        $contact = Contact::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(Contact::class, $client->contacts->first());
        $this->assertEquals($contact->id, $client->contacts->first()->id);
    }

    public function test_client_has_many_tasks()
    {
        $client = Client::factory()->create();
        $task = Task::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(Task::class, $client->tasks->first());
        $this->assertEquals($task->id, $client->tasks->first()->id);
    }

    public function test_client_has_many_activities()
    {
        $client = Client::factory()->create();
        $activity = Activity::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(Activity::class, $client->activities->first());
        $this->assertEquals($activity->id, $client->activities->first()->id);
    }

    public function test_contact_belongs_to_client()
    {
        $client = Client::factory()->create();
        $contact = Contact::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(Client::class, $contact->client);
        $this->assertEquals($client->id, $contact->client->id);
    }

    public function test_task_belongs_to_client_and_contact()
    {
        $client = Client::factory()->create();
        $contact = Contact::factory()->create(['client_id' => $client->id]);
        $task = Task::factory()->create(['client_id' => $client->id, 'contact_id' => $contact->id]);

        $this->assertInstanceOf(Client::class, $task->client);
        $this->assertInstanceOf(Contact::class, $task->contact);
        $this->assertEquals($client->id, $task->client->id);
        $this->assertEquals($contact->id, $task->contact->id);
    }

    public function test_activity_belongs_to_client_contact_task_user()
    {
        $client = Client::factory()->create();
        $contact = Contact::factory()->create(['client_id' => $client->id]);
        $task = Task::factory()->create(['client_id' => $client->id]);
        $user = User::factory()->create();
        $activity = Activity::factory()->create([
            'client_id' => $client->id,
            'contact_id' => $contact->id,
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Client::class, $activity->client);
        $this->assertInstanceOf(Contact::class, $activity->contact);
        $this->assertInstanceOf(Task::class, $activity->task);
        $this->assertInstanceOf(User::class, $activity->user);
    }

    public function test_database_foreign_key_constraints()
    {
        $client = Client::factory()->create();
        $contact = Contact::factory()->create(['client_id' => $client->id]);

        // Test that deleting client cascades or sets null appropriately
        $client->delete();

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
        // Contact should still exist since client is only soft deleted
        $this->assertDatabaseHas('contacts', ['id' => $contact->id]);
    }
}
