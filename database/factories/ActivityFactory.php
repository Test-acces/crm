<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => \App\Models\Client::factory(),
            'contact_id' => \App\Models\Contact::factory(),
            'task_id' => \App\Models\Task::factory(),
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->randomElement(['call', 'email', 'meeting', 'note', 'task_created', 'task_updated']),
            'description' => $this->faker->sentence(),
            'date' => $this->faker->dateTime(),
        ];
    }
}
