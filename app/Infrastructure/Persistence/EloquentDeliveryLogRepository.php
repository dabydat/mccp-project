<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\DeliveryLog as DomainDeliveryLog;
use App\Domain\Repositories\DeliveryLogRepository;
use App\Models\DeliveryLog as EloquentDeliveryLog;
use App\Domain\ValueObjects\Identity;
use App\Domain\ValueObjects\ChannelName;
use App\Domain\ValueObjects\DeliveryLogDetails;
use App\Domain\Enums\DeliveryStatus;

class EloquentDeliveryLogRepository implements DeliveryLogRepository
{
    public function save(DomainDeliveryLog $log): DomainDeliveryLog
    {
        $eloquentLog = EloquentDeliveryLog::create([
            'message_id' => $log->getMessageId()->value(),
            'channel' => $log->getChannel()->value(),
            'status' => $log->getStatus()->value,
            'details' => $log->getDetails()?->value(),
        ]);

        return new DomainDeliveryLog(
            new Identity($eloquentLog->id),
            new Identity($eloquentLog->message_id),
            new ChannelName($eloquentLog->channel),
            DeliveryStatus::from($eloquentLog->status),
            new DeliveryLogDetails($eloquentLog->details),
            new \DateTimeImmutable($eloquentLog->created_at)
        );
    }

    public function findByMessageId(Identity $messageId): array
    {
        return EloquentDeliveryLog::where('message_id', $messageId->value())
            ->get()
            ->map(fn($log) => new DomainDeliveryLog(
                new Identity($log->id),
                new Identity($log->message_id),
                new ChannelName($log->channel),
                DeliveryStatus::from($log->status),
                new DeliveryLogDetails($log->details),
                new \DateTimeImmutable($log->created_at)
            ))->toArray();
    }
}
