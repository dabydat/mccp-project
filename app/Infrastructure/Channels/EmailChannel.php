<?php

namespace App\Infrastructure\Channels;

use App\Domain\Entities\Message;
use App\Domain\Services\NotificationChannel;
use Illuminate\Support\Facades\Log;

class EmailChannel implements NotificationChannel
{
    public function send(Message $message): void
    {
        $payload = [
            'to' => 'user@example.com',
            'subject' => $message->getTitle()->value(),
            'body' => [
                'title' => $message->getTitle()->value(),
                'summary' => $message->getSummary()?->value(),
                'original_content' => $message->getContent()->value()
            ]
        ];

        Log::info("Email sent payload: " . json_encode($payload));
    }

    public function getName(): string { return 'email'; }
}
