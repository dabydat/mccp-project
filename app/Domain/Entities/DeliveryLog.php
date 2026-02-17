<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Identity;
use App\Domain\ValueObjects\ChannelName;
use App\Domain\ValueObjects\DeliveryLogDetails;
use App\Domain\Enums\DeliveryStatus;

class DeliveryLog
{
    public function __construct(
        private ?Identity $id,
        private Identity $messageId,
        private ChannelName $channel,
        private DeliveryStatus $status,
        private ?DeliveryLogDetails $details = null,
        private ?\DateTimeImmutable $createdAt = null
    ) {}

    public function getId(): ?Identity { return $this->id; }

    public function getMessageId(): Identity { return $this->messageId; }

    public function getChannel(): ChannelName { return $this->channel; }

    public function getStatus(): DeliveryStatus { return $this->status; }

    public function getDetails(): ?DeliveryLogDetails { return $this->details; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->value(),
            'message_id' => $this->messageId->value(),
            'channel' => $this->channel->value(),
            'status' => $this->status->value,
            'details' => $this->details?->value(),
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }
}
