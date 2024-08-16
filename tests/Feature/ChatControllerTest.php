<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Thread;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_messages_for_a_given_thread()
    {
        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a thread
        $thread = Thread::factory()->create(['sender_id' => $user1->id, 'receiver_id' => $user2->id]);

        // Create messages
        Message::factory()->create([
            'thread_id' => $thread->id,
            'sender_id' => $user1->id,
            'content' => 'Hello!',
        ]);
        Message::factory()->create([
            'thread_id' => $thread->id,
            'sender_id' => $user2->id,
            'content' => 'Hi there!',
        ]);

        // Make the request to fetch messages
        $response = $this->getJson('/api/messages?thread_id=' . $thread->id);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                [
                    'thread_id' => $thread->id,
                    'sender_id' => $user1->id,
                    'content' => 'Hello!',
                ],
                [
                    'thread_id' => $thread->id,
                    'sender_id' => $user2->id,
                    'content' => 'Hi there!',
                ],
            ]);
    }

    /** @test */
    public function it_can_send_a_message_successfully()
    {
        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a thread
        $thread = Thread::factory()->create(['sender_id' => $user1->id, 'receiver_id' => $user2->id]);

        // Authenticate as user1
        Auth::login($user1);

        // Make the request to send a message
        $response = $this->postJson('/api/messages', [
            'thread_id' => $thread->id,
            'sender_id' => $user1->id,
            'content' => 'Hello!',
        ]);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message_id' => Message::latest()->first()->id
            ]);

        // Assert that the message was created
        $this->assertDatabaseHas('messages', [
            'thread_id' => $thread->id,
            'sender_id' => $user1->id,
            'content' => 'Hello!',
        ]);
    }

    /** @test */
    public function it_returns_validation_error_when_sending_message_with_invalid_thread_id()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate as the user
        Auth::login($user);

        // Make the request to send a message with a non-existent thread ID
        $response = $this->postJson('/api/messages', [
            'thread_id' => 9999, // Non-existent thread ID
            'sender_id' => $user->id,
            'content' => 'Hello!',
        ]);

        // Assert the response
        $response->assertStatus(422)
            ->assertJsonValidationErrors('thread_id');
    }

    /** @test */
    public function it_returns_validation_error_when_sending_message_with_invalid_sender_id()
    {
        // Create a thread
        $thread = Thread::factory()->create();

        // Make the request to send a message with a non-existent sender ID
        $response = $this->postJson('/api/messages', [
            'thread_id' => $thread->id,
            'sender_id' => 9999, // Non-existent user ID
            'content' => 'Hello!',
        ]);

        // Assert the response
        $response->assertStatus(422)
            ->assertJsonValidationErrors('sender_id');
    }

    /** @test */
    public function it_returns_validation_error_when_sending_message_with_missing_content()
    {
        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a thread
        $thread = Thread::factory()->create(['sender_id' => $user1->id, 'receiver_id' => $user2->id]);

        // Authenticate as user1
        Auth::login($user1);

        // Make the request to send a message with missing content
        $response = $this->postJson('/api/messages', [
            'thread_id' => $thread->id,
            'sender_id' => $user1->id,
            'content' => '', // Empty content
        ]);

        // Assert the response
        $response->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }
}
