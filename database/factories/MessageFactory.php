<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'thread_id' => Thread::factory(), // Generates a new thread for thread_id
            'sender_id' => User::factory(), // Generates a new user for sender_id
            'content' => $this->faker->text, // Generates random content for the message
        ];
    }
}
