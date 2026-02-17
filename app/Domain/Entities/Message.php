<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Identity;
use App\Domain\ValueObjects\MessageTitle;
use App\Domain\ValueObjects\MessageContent;
use App\Domain\ValueObjects\MessageSummary;

class Message
{
    public function __construct(
        private ?Identity $id,
        private MessageTitle $title,
        private MessageContent $content,
        private ?MessageSummary $summary = null,
        private ?\DateTimeImmutable $createdAt = null
    ) {}

    public function getId(): ?Identity { return $this->id; }

    public function getTitle(): MessageTitle { return $this->title; }
    public function getContent(): MessageContent { return $this->content; }
    public function getSummary(): ?MessageSummary { return $this->summary; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }

    public function setSummary(MessageSummary $summary): void
    {
        $this->summary = $summary;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->value(),
            'title' => $this->title->value(),
            'content' => $this->content->value(),
            'summary' => $this->summary?->value(),
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }
}
