<?php

namespace App\Infrastructure\Channels;

use App\Domain\Entities\Message;
use App\Domain\Services\NotificationChannel;
use App\Infrastructure\Exceptions\ConfigurationException;
use App\Infrastructure\Exceptions\DeliveryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackChannel implements NotificationChannel
{
    public function send(Message $message): void
    {
        $url = config('services.slack.webhook_url');

        if (!$url) {
            throw ConfigurationException::missingConfig('services.slack.webhook_url');
        }

        $payload = [
            'text' => "*{$message->getTitle()->value()}*\n\n" .
                     "_*Summary:*_ {$message->getSummary()?->value()}\n\n" .
                     "_*Original Content:*_\n{$message->getContent()->value()}"
        ];

        $response = Http::post($url, $payload);

        if ($response->failed()) {
            throw DeliveryException::channelError('Slack', $response->body());
        }

        Log::info("Slack sent payload: " . json_encode($payload));
    }

    public function getName(): string { return 'slack'; }
}
