<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;

class ThreadControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_threads_for_authenticated_user()
    {
        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create threads
        Thread::factory()->create(['sender_id' => $user1->id, 'receiver_id' => $user2->id]);

        // Authenticate as user1
        Auth::login($user1);

        // Make the request
        $response = $this->getJson('/api/threads?userId=' . $user1->id);

        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'sender_id',
                    'receiver_id',
                    'sender' => ['id', 'nickname'],
                    'receiver' => ['id', 'nickname'],
                ],
            ]);
    }

    /** @test */
    public function it_returns_empty_list_if_no_threads_exist()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate as the user
        Auth::login($user);

        // Make the request
        $response = $this->getJson('/api/threads?userId=' . $user->id);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([]);
    }

    /** @test */
    public function it_can_create_a_new_thread()
    {
        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Authenticate as user1
        Auth::login($user1);

        // Make the request to create a thread
        $response = $this->postJson('/api/threads', [
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);

        // Assert the response
        $response->assertStatus(201)
            ->assertJson([
                'existing' => false,
                'thread' => [
                    'sender_id' => $user1->id,
                    'receiver_id' => $user2->id,
                ],
            ]);

        // Assert that the thread was created
        $this->assertDatabaseHas('threads', [
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);
    }

    /** @test */
    public function it_cannot_create_a_thread_with_invalid_receiver()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate as the user
        Auth::login($user);

        // Make the request to create a thread with an invalid receiver_id
        $response = $this->postJson('/api/threads', [
            'sender_id' => $user->id,
            'receiver_id' => 9999, // Non-existent user ID
        ]);

        // Assert the response
        $response->assertStatus(422)
            ->assertJsonValidationErrors('receiver_id');
    }

    /** @test */
    public function it_returns_existing_thread_if_it_already_exists()
    {
        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create an existing thread
        Thread::factory()->create(['sender_id' => $user1->id, 'receiver_id' => $user2->id]);

        // Authenticate as user1
        Auth::login($user1);

        // Make the request to create a thread
        $response = $this->postJson('/api/threads', [
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
        ]);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'existing' => true,
                'thread' => [
                    'sender_id' => $user1->id,
                    'receiver_id' => $user2->id,
                ],
            ]);
    }

    /** @test */
    public function it_returns_unauthorized_if_receiver_id_does_not_exist()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate as the user
        Auth::login($user);

        // Make the request to create a thread with a non-existent receiver_id
        $response = $this->postJson('/api/threads', [
            'sender_id' => $user->id,
            'receiver_id' => 9999, // Non-existent user ID
        ]);

        // Assert the response
        $response->assertStatus(422)
            ->assertJsonValidationErrors('receiver_id');
    }
}
