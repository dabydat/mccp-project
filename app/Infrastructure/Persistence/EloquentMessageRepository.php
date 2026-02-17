<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Message as DomainMessage;
use App\Domain\Repositories\MessageRepository;
use App\Models\Message as EloquentMessage;
use App\Domain\ValueObjects\Identity;
use App\Domain\ValueObjects\MessageTitle;
use App\Domain\ValueObjects\MessageContent;
use App\Domain\ValueObjects\MessageSummary;

class EloquentMessageRepository implements MessageRepository
{
    public function save(DomainMessage $message): DomainMessage
    {
        $eloquentMessage = EloquentMessage::updateOrCreate(
            ['id' => $message->getId()?->value()],
            [
                'title' => $message->getTitle()->value(),
                'content' => $message->getContent()->value(),
                'summary' => $message->getSummary()?->value(),
            ]
        );

        return new DomainMessage(
            new Identity($eloquentMessage->id),
            new MessageTitle($eloquentMessage->title),
            new MessageContent($eloquentMessage->content),
            $eloquentMessage->summary ? new MessageSummary($eloquentMessage->summary) : null,
            new \DateTimeImmutable($eloquentMessage->created_at)
        );
    }

    public function findById(Identity $id): ?DomainMessage
    {
        $eloquentMessage = EloquentMessage::find($id->value());
        if (!$eloquentMessage) return null;

        return new DomainMessage(
            new Identity($eloquentMessage->id),
            new MessageTitle($eloquentMessage->title),
            new MessageContent($eloquentMessage->content),
            $eloquentMessage->summary ? new MessageSummary($eloquentMessage->summary) : null,
            new \DateTimeImmutable($eloquentMessage->created_at)
        );
    }

    public function getAll(): array
    {
        return EloquentMessage::with('deliveryLogs')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'title' => $msg->title,
                    'summary' => $msg->summary,
                    'content' => $msg->content,
                    'created_at' => $msg->created_at,
                    'logs' => $msg->deliveryLogs->map(fn($log) => [
                        'channel' => $log->channel,
                        'status' => $log->status,
                        'details' => $log->details
                    ])
                ];
            })->toArray();
    }
}
