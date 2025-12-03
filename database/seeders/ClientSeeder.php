<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Client::factory(10)->create()->each(function ($client) {
            $client->contacts()->saveMany(\App\Models\Contact::factory(rand(1, 3))->make());
            $client->tasks()->saveMany(\App\Models\Task::factory(rand(1, 5))->make());
            $client->activities()->saveMany(\App\Models\Activity::factory(rand(1, 10))->make());
        });
    }
}
