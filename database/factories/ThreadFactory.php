<?php

namespace Database\Factories;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    protected $model = Thread::class;

    public function definition()
    {
        return [
            'sender_id' => User::factory(),  // Generates a new user for sender_id
            'receiver_id' => User::factory(), // Generates a new user for receiver_id
        ];
    }
}
