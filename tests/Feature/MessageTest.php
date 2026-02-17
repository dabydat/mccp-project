<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_messages_in_dashboard(): void
    {
        Message::create([
            'title' => 'Test Message',
            'content' => 'Full content here',
            'summary' => 'Short summary'
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Test Message');
    }

    public function test_it_processes_and_distributes_content(): void
    {
        $payload = [
            'title' => 'New Content',
            'content' => 'This is a long content to be summarized by IA.',
            'channels' => ['email', 'sms']
        ];

        $response = $this->post('/messages', $payload);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('messages', [
            'title' => 'New Content'
        ]);
        $this->assertDatabaseHas('delivery_logs', [
            'channel' => 'email',
            'status' => 'success'
        ]);
    }
}
